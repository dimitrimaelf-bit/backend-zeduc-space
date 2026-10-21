<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    // Affiche la liste des reclamations dans une vue Blade
    public function index(Request $request)
    {
        $query = Claim::with([
            'user:id,first_name,last_name,email,phone',
            'order:id,order_number,total_fcfa,created_at',
            'handler:id,first_name,last_name',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->orderBy('created_at', 'desc')->get();

        return view('admin.claims.index', compact('claims'));
    }

    // Formulaire pour repondre a une reclamation
    public function edit($id)
    {
        $claim = Claim::with(['user', 'order', 'handler'])->findOrFail($id);

        return view('admin.claims.edit', compact('claim'));
    }

    // Traite la reclamation et sauvegarde la decision
    public function update(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);

        $request->merge([
            'status' => match ($request->input('status')) {
                'en_attente' => 'open',
                'résolu' => 'resolved',
                default => $request->input('status'),
            },
            'response' => $request->input('response', $request->input('admin_response')),
        ]);

        $validated = $request->validate([
            'status'   => 'required|in:open,in_progress,resolved,rejected',
            'response' => 'required|string',
        ]);

        $claim->update([
            'status'     => $validated['status'],
            'response'   => $validated['response'],
            'handled_by' => auth()->id,
        ]);

        return redirect()->route('admin.claims.index')
            ->with('success', 'La reclamation a ete mise a jour.');
    }
}