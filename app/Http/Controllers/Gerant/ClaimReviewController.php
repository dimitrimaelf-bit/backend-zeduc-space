<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClaimReviewController extends Controller
{
    private const STATUSES = [
        'open',
        'in_progress',
        'resolved',
        'rejected',
    ];

    public function index(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $filters = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(self::STATUSES)],
        ]);

        $claims = Claim::query()
            ->with([
                'user:id,first_name,last_name,email',
                'order:id,order_number,status',
                'handler:id,first_name,last_name',
            ])
            ->when(
                isset($filters['status']),
                fn ($query) => $query->where('status', $filters['status'])
            )
            ->orderByDesc('created_at')
            ->paginate(15);

        $counts = Claim::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $summary = array_fill_keys(self::STATUSES, 0);

        foreach ($counts as $status => $count) {
            $summary[$status] = (int) $count;
        }

        
    }

    public function review(Request $request, Claim $claim)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $validated = $request->validate([
            'decision' => ['required', 'string', Rule::in(['approved', 'rejected'])],
        ]);

        if ($claim->status !== 'in_progress' || blank($claim->response)) {
            return response()->json([
                'message' => 'Cette réclamation ne contient aucune réponse d’employé à examiner.',
            ], 422);
        }

        $claim->update([
            'status' => $validated['decision'] === 'approved' ? 'resolved' : 'rejected',
        ]);

       
    }
}
