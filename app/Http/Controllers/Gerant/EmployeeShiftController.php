<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\EmployeeShift;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeShiftController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'employee_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role_id', $this->employeeRoleId()),
            ],
        ]);

        $query = EmployeeShift::query()->with('employee')->orderByDesc('shift_date')->orderByDesc('started_at');

        if (! empty($filters['start_date'])) {
            $query->whereDate('shift_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('shift_date', '<=', $filters['end_date']);
        }

        if (! empty($filters['employee_id'])) {
            $query->where('user_id', $filters['employee_id']);
        }

        $shifts = $query->paginate(15)->withQueryString();
        $employees = User::query()
            ->where('role_id', $this->employeeRoleId())
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name']);

        $completeShifts = (clone $query)->whereNotNull('ended_at')->get(['started_at', 'ended_at']);
        $totalMinutes = $completeShifts->sum(fn (EmployeeShift $shift) => $shift->duration_minutes);

        return view('Gerant.employee-shifts.index', [
            'shifts' => $shifts,
            'employees' => $employees,
            'filters' => $filters,
            'shiftCount' => (clone $query)->count(),
            'totalMinutes' => $totalMinutes,
        ]);
    }

    public function start(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'employee', 403);
        abort_unless($request->user()->is_active, 403);

        $validated = $request->validate([
            'service' => ['nullable', 'string', 'max:80'],
        ]);

        if ($request->user()->shifts()->whereNull('ended_at')->exists()) {
            return response()->json(['message' => 'Un shift est déjà en cours.'], 409);
        }

        $shift = $request->user()->shifts()->create([
            'shift_date' => now()->toDateString(),
            'service' => $validated['service'] ?? null,
            'started_at' => now(),
            'status' => 'open',
        ]);

        return response()->json(['shift' => $shift], 201);
    }

    public function finish(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'employee', 403);
        abort_unless($request->user()->is_active, 403);

        $shift = $request->user()->shifts()
            ->whereNull('ended_at')
            ->latest('started_at')
            ->firstOrFail();
        $shift->update([
            'ended_at' => now(),
            'status' => 'pending',
        ]);

        return response()->json(['shift' => $shift->fresh()], 200);
    }

    public function validateShift(Request $request, EmployeeShift $shift)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);
        abort_if($shift->ended_at === null, 422, 'Un shift en cours ne peut pas être validé.');

        $shift->update(['status' => 'validated']);

        if ($request->expectsJson()) {
            return response()->json(['shift' => $shift->fresh('employee')]);
        }

        return back()->with('status', 'Le shift a été validé.');
    }

    private function employeeRoleId(): ?int
    {
        return Role::query()->where('code', 'employee')->value('id');
    }
}
