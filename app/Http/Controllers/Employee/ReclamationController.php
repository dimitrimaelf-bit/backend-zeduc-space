<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $query = Claim::with(['user','order'])->latest();
        if ($request->filled('search')) {
            $term = $request->string('search')->toString();
            $query->where(function ($q) use ($term) {
                $q->where('objet', 'like', "%{$term}%")
                  ->orWhere('sujet', 'like', "%{$term}%")
                  ->orWhere('message', 'like', "%{$term}%");
            });
        }
        if ($request->filled('statut')) $query->where('statut', $request->input('statut'));
        $reclamations = $query->paginate(15)->withQueryString();
        return view('employe.reclamations.index', compact('reclamations'));
    }

    public function show(Claim $reclamation)
    {
        $reclamation->load(['user','order']);
        return view('employe.reclamations.show', compact('reclamation'));
    }

    public function update(Request $request, Claim $reclamation)
    {
        $validated = $request->validate([
            'reponse' => ['required','string','max:5000'],
            'statut' => ['required','in:Ouverte,En cours,Résolue'],
        ]);
        $reclamation->reponse = $validated['reponse'];
        $reclamation->statut = $validated['statut'];
        if (auth()->check() && isset($reclamation->handled_by)) $reclamation->handled_by = auth()->id();
        $reclamation->save();
        return redirect()->route('employe.reclamations.show', $reclamation)->with('success', 'Réclamation mise à jour.');
    }
}
