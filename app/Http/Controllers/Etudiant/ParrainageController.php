<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParrainageController extends Controller
{
    // Alphabet sans 0/O ni 1/I pour éviter les erreurs de saisie
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    /** POST : bouton "Générer un nouveau code" */
    public function generer(Request $request)
    {
        $user = $request->user();

        // On DÉSACTIVE l'ancien code au lieu de le supprimer :
        // les parrainages déjà faits pointent encore vers lui (clé étrangère)
        ReferralCode::where('user_id', $user->id)->where('is_active', true)->update(['is_active' => false]);

        // Préfixe = 5 premières lettres du prénom sans accents (Élodie -> ELODI)
        $prefixe = substr(preg_replace('/[^A-Z]/', '', strtoupper(Str::ascii($user->first_name))), 0, 5) ?: 'MIAM';

        do {
            $suffixe = '';
            for ($i = 0; $i < 3; $i++) {
                $suffixe .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
            }
            $code = "$prefixe-$suffixe";                       // ex : MARIE-7K2
        } while (ReferralCode::where('code', $code)->exists()); // colonne code = UNIQUE

        ReferralCode::create(['user_id' => $user->id, 'code' => $code, 'is_active' => true]);

        return back()->with('success', "Votre nouveau code est $code.");
    }

    /** POST : saisir le code de SON parrain */
    public function utiliser(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'max:30']]);
        $user = $request->user();

        $code = ReferralCode::where('code', strtoupper(trim($request->code)))
            ->where('is_active', true)->first();

        // Chaque règle bloque une fraude possible
        $erreur = match (true) {
            ! $code                                                   => "Ce code n'existe pas ou n'est plus actif.",
            $code->user_id === $user->id                              => 'Vous ne pouvez pas utiliser votre propre code.',
            Referral::where('referred_user_id', $user->id)->exists() => 'Vous avez déjà un parrain.',
            $user->orders()->exists()                                 => 'Un code ne peut être utilisé qu’avant votre première commande.',
            default                                                   => null,
        };

        if ($erreur) {
            return back()->withErrors(['code' => $erreur]);
        }

        // Le parrain sera récompensé quand la 1re commande du filleul sera "completed"
        Referral::create([
            'referrer_user_id' => $code->user_id,
            'referred_user_id' => $user->id,
            'code_id'          => $code->id,
            'status'           => 'pending',
        ]);

        return back()->with('success', 'Code de parrainage enregistré.');
    }
}