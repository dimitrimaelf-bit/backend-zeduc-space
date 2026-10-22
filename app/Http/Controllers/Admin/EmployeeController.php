<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    /**
     * Méthode privée pour récupérer le rôle cible (employee par défaut, ou gerant)
     */
    private function getTargetRole(Request $request)
    {
        $roleCode = $request->query('role', 'employee');
        abort_unless(in_array($roleCode, ['employee', 'gerant', 'manager'], true), 404);

        $codes = $roleCode === 'employee' ? ['employee'] : ['gerant', 'manager'];

        return Role::whereIn('code', $codes)->firstOrFail();
    }

    public function index(Request $request)
    {
        $roleFilter = $request->query('role', 'all');
        abort_unless(in_array($roleFilter, ['all', 'employee', 'gerant', 'manager'], true), 404);
        $roleCodes = match ($roleFilter) {
            'employee' => ['employee'],
            'gerant', 'manager' => ['gerant', 'manager'],
            default => ['employee', 'gerant', 'manager'],
        };
        $staffRoleIds = Role::whereIn('code', $roleCodes)->pluck('id');
        $query = User::with('role')->whereIn('role_id', $staffRoleIds);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $employees = $query->latest()->paginate(15)->withQueryString();

        return view('admin.employees.index', compact('employees', 'roleFilter'));
    }

    public function create(Request $request)
    {
        $role = $this->getTargetRole($request);

        return view('admin.employees.create', compact('role'));
    }

    public function store(Request $request)
    {
        $roleCode = $request->input('role_code', 'employee');
        abort_unless(in_array($roleCode, ['employee', 'gerant', 'manager'], true), 422);
        $role = Role::whereIn('code', $roleCode === 'employee' ? ['employee'] : ['gerant', 'manager'])->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'required|email|unique:users,email|max:255',
            'phone'      => 'nullable|string|max:20',
            'password'   => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
        ]);

        User::create([
            'role_id'       => $role->id,
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'password'      => $validated['password'],
            'is_active'     => true,
        ]);

        $label = ($role->code === 'gerant') ? 'gérant' : 'employé';

        return redirect()->route('admin.employees.index', ['role' => $role->code])
            ->with('success', "Le compte {$label} a été créé avec succès.");
    }

    public function edit($id)
    {
        $employee = $this->findStaffMember($id);
        $role = $employee->role;

        return view('admin.employees.edit', compact('employee', 'role'));
    }

    public function update(Request $request, $id)
    {
        $employee = $this->findStaffMember($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'required|email|max:255|unique:users,email,' . $id,
            'phone'      => 'nullable|string|max:20',
            'is_active'  => 'sometimes|boolean',
        ]);

        $employee->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'is_active'  => $validated['is_active'] ?? $employee->is_active,
        ]);

        $roleCode = $employee->role ? $employee->role->code : 'employee';
        $label = ($roleCode === 'gerant') ? 'gérant' : 'employé';

        return redirect()->route('admin.employees.index', ['role' => $roleCode])
            ->with('success', "Le compte {$label} a été mis à jour avec succès.");
    }

    public function destroy($id)
    {
        $employee = $this->findStaffMember($id);
        $roleCode = $employee->role ? $employee->role->code : 'employee';
        $label = ($roleCode === 'gerant') ? 'gérant' : 'employé';

        $employee->delete();

        return redirect()->route('admin.employees.index', ['role' => $roleCode])
            ->with('success', "Le {$label} a été supprimé.");
    }

    private function findStaffMember(int|string $id): User
    {
        $employee = User::with('role')->findOrFail($id);
        abort_unless(in_array($employee->role?->code, ['employee', 'gerant', 'manager'], true), 404);

        return $employee;
    }
}