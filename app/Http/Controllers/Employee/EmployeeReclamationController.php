<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;

class EmployeeReclamationController extends Controller
{
    /**
     * Afficher les réclamations ouvertes ou en cours.
     */
    public function index()
    {
        $claims = Claim::with(['user', 'order'])
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Réclamations à traiter.',
            'data' => $claims,
        ]);
    }

    /**
     * Consulter une réclamation.
     */
    public function show(int $id)
    {
        $claim = Claim::with(['user', 'order'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $claim,
        ]);
    }

    /**
     * Répondre à une réclamation.
     * La validation définitive reste du ressort du gérant.
     */
    public function replyToReclamation(Request $request, int $id)
    {
        $validated = $request->validate([
            'response' => ['required', 'string', 'max:5000'],
        ]);

        $claim = Claim::findOrFail($id);

        if (!in_array($claim->status, ['open', 'in_progress'], true)) {
        return back()->with('error', 'Cette réclamation ne peut plus être traitée.');
    }

        $claim->response = $validated['response'];
        $claim->status = 'in_progress';
        $claim->handled_by = $request->user()->id;
        $claim->save();

    }
}
