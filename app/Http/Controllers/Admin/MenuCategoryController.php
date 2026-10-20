<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;


class MenuCategoryController extends Controller //Classe gerant la logique des categories de menus (Plats, Boissons, etc.)
{
   
    public function index() //Affiche la liste des categories de menu
    {
        $categories = MenuCategory::orderBy('sort_order', 'asc')->get(); // Recuperation de toutes les categories triees par ordre d'affichage croissant
        return view('admin.categories.index', compact('categories')); // Rendu de la vue Blade et transmission de la variable $categories
    }

    public function create() //Affiche le formulaire de creation de categorie
    {
        return view('admin.categories.create');// Rendu du formulaire Blade d'ajout
    }
    
    public function store(Request $request) // Methode de traitement du formulaire de creation et enregistrement d'une nouvelle categorie
    {
        
        $validated = $request->validate([  // Validation des regles pour une categorie
            'name'       => 'required|string|unique:menu_categories,name|max:100', // Nom unique obligatoire
            'sort_order' => 'required|integer|min:0', // Ordre de tri sous forme d'entier
            'is_active'  => 'required|boolean', // Booleen indiquant si la categorie est active
        ]);
       
        MenuCategory::create($validated);  // Enregistrement effectif de la nouvelle categorie dans la base MySQL
        return redirect()->route('admin.categories.index')  // Redirection vers la liste des categories avec un message de succes
            ->with('success', 'Categorie creee avec succes.');
    }
        public function edit($id)// Methode affichant l'ecran d'edition et le formulaire de modification de categorie
    {
        $category = MenuCategory::findOrFail($id); // Recherche de la categorie par son identifiant ou levee d'une erreur 404
        return view('admin.categories.edit', compact('category')); // Chargement de la vue Blade avec l'objet categorie
    }

    public function update(Request $request, $id)  //Methode enregistrant les modifications/mise a jour apportees a une categorie
    {
        $category = MenuCategory::findOrFail($id); // Recuperation de l'enregistrement existant
        $validated = $request->validate([ // Validation des donnees modifiees
            'name'       => 'required|string|max:100|unique:menu_categories,name,' . $id,  // Verification du nom en ignorant l'enregistrement en cours
            'sort_order' => 'required|integer|min:0', // Ordre de tri valide
            'is_active'  => 'required|boolean',  // Statut actif/inactif
        ]);
        $category->update($validated); // Application des modifications en base de donnees
        return redirect()->route('admin.categories.index') // Redirection vers l'index avec notification
            ->with('success', 'Categorie mise a jour avec succes.');
    }

    public function destroy($id)// Methode de suppression definitive d'une categorie
    {
        $category = MenuCategory::findOrFail($id); // Recherche de la categorie ciblee
        $category->delete(); // Execution de la suppression dans la base de donnees
        return redirect()->route('admin.categories.index')// Redirection avec message de confirmation
            ->with('success', 'Categorie supprimee avec succes.');
    }
}