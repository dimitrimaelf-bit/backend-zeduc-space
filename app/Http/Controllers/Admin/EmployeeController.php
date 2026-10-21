<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    /**
     * Méthode privée pour récupérer le rôle cible (employee par défaut, ou gerant)
     */
    private function getTargetRole(Request $request)
    {
        $roleCode = $request->query('role', 'employee');
        return Role::where('code', $roleCode)->firstOrFail();
    }

    public function index(Request $request)
    {
        $role = $this->getTargetRole($request);
        
        $employees = User::where('role_id', $role->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.employees.index', compact('employees', 'role'));
    }

    public function create(Request $request)
    {
        $role = $this->getTargetRole($request);

        return view('admin.employees.create', compact('role'));
    }

    public function store(Request $request)
    {
        $roleCode = $request->input('role_code', 'employee');
        $role = Role::where('code', $roleCode)->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'required|email|unique:users,email|max:255',
            'phone'      => 'nullable|string|max:20',
            'password'   => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
        ]);

        $hashedPassword = Hash::make($validated['password']);

        User::create([
            'role_id'       => $role->id,
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'password'      => $hashedPassword,
            'password_hash' => $hashedPassword,
            'is_active'     => true,
        ]);

        $label = ($role->code === 'gerant') ? 'gérant' : 'employé';

        return redirect()->route('admin.employees.index', ['role' => $role->code])
            ->with('success', "Le compte {$label} a été créé avec succès.");
    }

    public function edit($id)
    {
        $employee = User::with('role')->findOrFail($id);
        $role = $employee->role;

        return view('admin.employees.edit', compact('employee', 'role'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::with('role')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'required|email|max:255|unique:users,email,' . $id,
            'phone'      => 'nullable|string|max:20',
        ]);

        $employee->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
        ]);

        $roleCode = $employee->role ? $employee->role->code : 'employee';
        $label = ($roleCode === 'gerant') ? 'gérant' : 'employé';

        return redirect()->route('admin.employees.index', ['role' => $roleCode])
            ->with('success', "Le compte {$label} a été mis à jour avec succès.");
    }

    public function destroy($id)
    {
        $employee = User::with('role')->findOrFail($id);
        $roleCode = $employee->role ? $employee->role->code : 'employee';
        $label = ($roleCode === 'gerant') ? 'gérant' : 'employé';

        $employee->delete();

        return redirect()->route('admin.employees.index', ['role' => $roleCode])
            ->with('success', "Le {$label} a été supprimé.");
    }
}