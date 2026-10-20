<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller  //Controleur des reglages generaux (points de fidelite, horaires)
{
    public function index()  //affiche la page de configuration Blade et lesvariables de configuration
    {
       $settings = AppSetting::with('updatedBy:id,first_name,last_name')->get(); // Recuperation de tous les parametres avec le nom de l'administrateur qui les a modifies
        return view('admin.settings.index', compact('settings')); // Affichage de la vue de gestion des parametres
    }
    public function update(Request $request, $key)  //Enregistre les nouveaux parametres globaux et mise a jour d'une cle de configuration
    {
         $setting = AppSetting::where('setting_key', $key)->firstOrFail();// Recherche du parametre par sa cle unique dans la table
        $validated = $request->validate([ // Validation de la valeur soumise
                'setting_value' => 'required|string', // La valeur doit etre renseignee sous forme de chaine
        ]);
        $setting->update([  // Mise a jour de la valeur et de l'historique d'auteur
            'setting_value' => $validated['setting_value'],  // Enregistrement de la nouvelle valeur du parametre
            'updated_by'    => auth()->id(), // Tracabilite de l'administrateur qui fait la modification
        ]);
        return redirect()->route('admin.settings.index') // Redirection vers la page de reglages avec confirmation
            ->with('success', "Le paramètre '{$key}' a été mis à jour.");
    }
}