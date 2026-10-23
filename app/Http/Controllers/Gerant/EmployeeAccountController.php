<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class EmployeeAccountController extends Controller
{
    public function index(Request $request)
    {
        abort_unless((int) $request->user()?->role_id === 3, 403);

        $employees = User::query()
            ->whereHas('role', fn ($query) => $query->where('code', 'employee'))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('Gerant.Employeeaccounts', compact('employees'));
    }

    /**
     * Crée un compte employé actif à la demande d'un gérant.
     */
    public function store(Request $request)
    {
        // Seul un gérant peut créer un compte dans l'équipe.
        abort_unless((int) $request->user()?->role_id === 3, 403);

        // Valide les données personnelles, l'unicité de l'adresse et la confirmation du mot de passe.
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Récupère le rôle employé et l'associe au nouveau compte, activé par défaut.
        $employeeRole = Role::query()->firstOrCreate(
            ['code' => 'employee'],
            ['label' => 'Employé']
        );
        User::create([
            ...$validated,
            'role_id' => $employeeRole->id,
            'is_active' => true,
        ]);

        return redirect()
            ->route('gerant.employees.index')
            ->with('status', 'Le compte employé a été créé.');
    }
}