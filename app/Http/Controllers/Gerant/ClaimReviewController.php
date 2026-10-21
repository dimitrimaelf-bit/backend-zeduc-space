<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClaimReviewController extends Controller
{
    // Ces états servent à valider le filtre et à afficher un compteur pour chaque catégorie.
    private const STATUSES = [
        'open',
        'in_progress',
        'resolved',
        'rejected',
    ];

    public function index(Request $request)
    {
        // L'historique global des réclamations est réservé aux gérants.
        abort_unless((int) $request->user()?->role_id === 3, 403);

        // Le filtre est facultatif, mais sa valeur doit correspondre à un état autorisé.
        $filters = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(self::STATUSES)],
        ]);

        // Charge aussi le demandeur, la commande et l'employé chargé du dossier.
        // Cette prélecture évite de refaire une requête pour chaque réclamation affichée.
        $claims = Claim::query()
            ->with([
                'user:id,first_name,last_name,email',
                'order:id,order_number,status',
                'handler:id,first_name,last_name',
            ])
            // Applique le filtre seulement si le gérant a choisi un état.
            ->when(
                isset($filters['status']),
                fn ($query) => $query->where('status', $filters['status'])
            )
            // Les dossiers les plus récemment créés apparaissent en premier.
            ->orderByDesc('created_at')
            // Limite la liste à 15 résultats par page.
            ->paginate(15);

        // Les compteurs représentent tous les dossiers : ils ne dépendent pas du filtre de la liste.
        $counts = Claim::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        // Prépare d'abord tous les états à zéro, y compris ceux sans réclamation.
        $summary = array_fill_keys(self::STATUSES, 0);

        // Remplace les zéros par les nombres réellement renvoyés par la base.
        foreach ($counts as $status => $count) {
            $summary[$status] = (int) $count;
        }
     return view('Gerant.Claims', [
         'claims' => $claims,
         'summary' => $summary,
         'selectedClaim' => $claims->first(),
     ]);
        
    }

    public function review(Request $request, Claim $claim)
    {
        // Seul un gérant peut prendre la décision finale sur une réponse d'employé.
        abort_unless((int) $request->user()?->role_id === 3, 403);

        // Le gérant doit choisir l'une des deux décisions prévues.
        $validated = $request->validate([
            'decision' => ['required', 'string', Rule::in(['approved', 'rejected'])],
        ]);

        // Une réclamation ouverte ou sans réponse n'est pas prête à être examinée.
        if ($claim->status !== 'in_progress' || blank($claim->response)) {
            return back()
                ->withErrors(['decision' => 'Cette réclamation ne contient aucune réponse d’employé à examiner.'])
                ->withInput();
        }

        // Une réponse approuvée clôt le dossier comme résolu ; un refus le marque comme rejeté.
        $claim->update([
            'status' => $validated['decision'] === 'approved' ? 'resolved' : 'rejected',
        ]);

      return redirect()
          ->route('gerant.claims.index')
          ->with('status', 'La réclamation a été examinée.');
    }
}
