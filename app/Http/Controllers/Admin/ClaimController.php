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

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(function ($query) use ($search) {
                $query->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"))
                    ->orWhereHas('order', fn ($orderQuery) => $orderQuery
                        ->where('order_number', 'like', "%{$search}%"));
            });
        }

        $claims = $query->latest()->paginate(15)->withQueryString();

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

        $validated = $request->validate([
            'status'   => 'required|in:open,in_progress,resolved,rejected',
            'response' => 'nullable|string|max:5000',
        ]);

        $claim->update([
            'status'     => $validated['status'],
            'response'   => $validated['response'] ?? null,
            'handled_by' => auth()->id(),
        ]);

        return redirect()->route('admin.claims.index')
            ->with('success', 'La reclamation a ete mise a jour.');
    }
}