<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller// ContrOleur gerant les offres et affiches promotionnelles
{
     public function index() //Affiche la liste des promotions dans la vue Blade. Affichage des promotions existantes
   {
$promotions = Promotion::orderBy('created_at', 'desc')->get(); //Recuperation de la liste triee par la date de creation la plus recente
    return view('admin.promotions.index', compact('promotions')); // Envoi des donnees a la vue Blade dediee
    }
        public function create()//Formulaire de creation d'une promotion : Methode affichant le formulaire de creation
     {
        return view('admin.promotions.create'); // Renvoie de la vue Blade de creation
    }

    public function store(Request $request)//Enregistre la promotion et redirige : Sauvegarde d'une nouvelle promotion
    {
        $validated = $request->validate([// Validation des regles pour une offre promotionnelle
            'type'        => 'required|in:promotion,event', // Type : promotion ou événement
           'title'       => 'required|string|max:150', // Titre de la promotion obligatoire
            'description' => 'nullable|string',// Description detaillee optionnelle
            'image_url'   => 'nullable|url|max:500',// URL de l'affiche publicitaire
            'starts_at'   => 'nullable|date',// Date de debut d'affichage
            'ends_at'     => 'nullable|date|after_or_equal:starts_at', // Date de fin devant etre posterieure ou egale a la date de debut
            'is_active'   => 'boolean',  // Statut actif
        ]);

        
        $validated['is_active'] = $request->has('is_active');// Affectation du statut actif selon la case a cocher
         Promotion::create($validated); // Creation de la promotion en base de donnees
        return redirect()->route('admin.promotions.index') // Redirection vers l'index des promotions
            ->with('success', 'La promotion a ete enregistree.');
    }
   
    public function edit($id) // Formulaire d'eedition de la promotion : Methode de preparation de l'edition
    {
        $promotion = Promotion::findOrFail($id);// Recherche de la promotion ciblee
        return view('admin.promotions.edit', compact('promotion')); // Rendu du formulaire de modification avec les donnees
    }
       
    public function update(Request $request, $id) //Met a jour une promotion.Sauvegarde des donnees modifiees
    {
        $promotion = Promotion::findOrFail($id);// Recuperation de l'enregistrement a mettre a jour
        $validated = $request->validate([// Validation des champs modifies
            'type'        => 'required|in:promotion,event', // Type : promotion ou événement
            'title'       => 'required|string|max:150',// Titre requis
            'description' => 'nullable|string',// Description
            'image_url'   => 'nullable|url|max:500', // URL d'image validee
            'starts_at'   => 'nullable|date',// Date de debut
            'ends_at'     => 'nullable|date|after_or_equal:starts_at',// Date de fin coherente
            'is_active'   => 'boolean', // Case a cocher actif/inactif
        ]);

        $validated['is_active'] = $request->has('is_active');// Mise a jour de la valeur booleenne d'activation
        $promotion->update($validated); // Application de la mise a jour
        return redirect()->route('admin.promotions.index')  // Redirection avec confirmation
            ->with('success', 'La promotion a ete mise a jour.');
    }

    public function destroy($id)//Supprime une promotion/une offre
    {
        $promotion = Promotion::findOrFail($id);// Recherche de la promotion par ID
        $promotion->delete(); // Suppression de la base de donnees
        return redirect()->route('admin.promotions.index')// Redirection vers la liste des promotions
            ->with('success', 'La promotion a été supprimée.');
    }
}