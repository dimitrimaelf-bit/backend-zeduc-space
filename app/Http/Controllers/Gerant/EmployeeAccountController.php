<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class EmployeeAccountController extends Controller
{
    public function store(Request $request)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $employeeRole = Role::query()->where('code', 'employee')->firstOrFail();
        $employee = User::create([
            ...$validated,
            'role_id' => $employeeRole->id,
            'is_active' => true,
        ]);

        
    }

    public function updateAccess(Request $request, User $employee)
    {
        abort_unless($request->user()?->role?->code === 'manager', 403);
        abort_unless($employee->role?->code === 'employee', 404);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $employee->update(['is_active' => $validated['is_active']]);

        
    }
}