<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)  //Methode d'affichage de la carte des elements du menu
    {
        $query = MenuItem::with('category');// Preparation de la requete avec prechargement de la relation category (evite le probleme SQL N+1)
        if ($request->filled('category_id')) { // Filtrage dynamique si une categorie specifique est selectionnee
            $query->where('category_id', $request->category_id); // Ajout du filtre par ID de categorie
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = MenuCategory::where('is_active', true)->get(); // Recuperation de toutes les categories actives pour alimenter un filtre de recherche
        return view('admin.menu.index', compact('items', 'categories')); // Transmission des elements et categories e la vue Blade
    }

    public function create() // Methode pour afficher le formulaire de creation/d'ajout d'un plat ou d'une boisson.
    {
       $categories = MenuCategory::where('is_active', true)->get(); // Recuperation de la liste des categories actives pour le champ 'select'
        return view('admin.menu.create', compact('categories')); // Affichage de la vue de creation
    }

    public function store(Request $request) // Methode de sauvegarde/Enregistrement d'un nouvel element
    {
        $validated = $request->validate([ // Validation stricte des donnees envoyees par le formulaire
            'category_id'      => 'nullable|exists:menu_categories,id', // ID de categorie optionnel mais devant exister dans la table menu_categories
            'name'             => 'required|string|max:150', // Intitule du plat obligatoire
            'description'      => 'nullable|string',  // Description optionnelle
            'price_fcfa'       => 'required|integer|min:0', // Prix en FCFA obligatoire et positif
            'image_url'        => 'nullable|url|max:500',// Lien vers l'image valide comme une URL valide
            'is_available'     => 'boolean',   // Disponibilite (booleen)
            'is_daily_special' => 'boolean', // Indicateur du plat du jour (booleen)
        ]);

        // Conversion du champ case a cocher en valeur booleenne explicite
        $validated['is_available'] = $request->has('is_available');

        // Conversion de la case du plat du jour en booleen
        $validated['is_daily_special'] = $request->has('is_daily_special');
        $validated['description'] = $validated['description'] ?? '';
        
        if ($validated['is_daily_special']) {   // Regle metier : S'il s'agit du plat du jour, desactiver l'ancien plat du jour unique
        MenuItem::where('is_daily_special', true)->update(['is_daily_special' => false]); // Remise e zero de tous les autres plats du jour
        }
        MenuItem::create($validated); // Creation de l'enregistrement en BDD 
        return redirect()->route('admin.menu.index') // Redirection vers le tableau du menu
            ->with('success', 'L\'element du menu a ete ajoute avec succes.');
    }

    public function edit($id) //Affiche le formulaire d'edition d'un plat.
    {
        
        $item = MenuItem::findOrFail($id);// Recuperation de l'article par son ID
        $categories = MenuCategory::where('is_active', true)->get(); // Recuperation des categories actives

        // Rendu de la vue avec injection de l'article et des categories
        return view('admin.menu.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)  // Methode d'enregistrement des modifications/mise a jour d'un plat
    {
        
        $item = MenuItem::findOrFail($id);// Recuperation de l'enregistrement cible
        $validated = $request->validate([// Validation des modifications
           
            'category_id'      => 'nullable|exists:menu_categories,id', // Validation de la categorie
            'name'             => 'required|string|max:150',// Validation du nom du plat
            'description'      => 'nullable|string',// Validation de la description
            'price_fcfa'       => 'required|integer|min:0', // Validation du prix en FCFA
            'image_url'        => 'nullable|url|max:500', // Validation de l'URL de l'image
            'is_available'     => 'boolean', // Booleen disponibilite
            'is_daily_special' => 'boolean',// Booleen plat du jour
        ]);

        $validated['is_available'] = $request->has('is_available'); // Gestion du statut disponible via la checkbox
        $validated['is_daily_special'] = $request->has('is_daily_special'); // Gestion du statut du plat du jour via la checkbox
        $validated['description'] = $validated['description'] ?? '';
        if ($validated['is_daily_special']) { // Si coche comme plat du jour, decocher tous les autres plats sauf celui-ci 
            MenuItem::where('id', '<>', $id)->update(['is_daily_special' => false]); // Mise a jour de la table pour reinitialiser les autres plats
        }
        $item->update($validated); // Sauvegarde des changements
        return redirect()->route('admin.menu.index')// Redirection vers le menu
            ->with('success', 'L\'article du menu a ete mis a jour.');
    }

    public function destroy($id) //Supprime un article du menu.
    {
       $item = MenuItem::findOrFail($id); // Recherche du plat a supprimer
       $item->delete();// Suppression de la ligne en base de donnees
        return redirect()->route('admin.menu.index') // Redirection vers la liste du menu
        ->with('success', 'L\'article a ete supprime de la carte.');
    }
}