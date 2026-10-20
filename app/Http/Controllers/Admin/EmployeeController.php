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
    public function index(Request $request)
    {
        $employeeRole = Role::where('code', 'employee')->firstOrFail();
        $query = User::where('role_id', $employeeRole->id);

        $employees = $query->orderBy('created_at', 'desc')->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'required|email|unique:users,email|max:255',
            'phone'      => 'nullable|string|max:20',
            'password'   => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
        ]);

        $employeeRole = Role::where('code', 'employee')->firstOrFail();

        User::create([
            'role_id'       => $employeeRole->id,
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'password_hash' => Hash::make($validated['password']),
            'is_active'     => true,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Le compte employe a ete cree avec succes.');
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

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

        return redirect()->route('admin.employees.index')
            ->with('success', 'Le compte employe a ete mis a jour avec succes.');
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'L\'employe a ete supprime.');
    }
}