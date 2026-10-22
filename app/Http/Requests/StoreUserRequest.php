<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Override;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
                Rule::in([1, 2, 3, 4]),
            ],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'referral_code' => [
                'nullable',
                'string',
                Rule::exists('referral_codes', 'code')->where('is_active', true),
            ],
        ];
    }

    #[Override]
    protected function prepareForValidation()
    {
        if (! is_string($this->role_id) || is_numeric($this->role_id)) {
            return;
        }

        $roleCodes = [
            'etudiant' => 'student',
            'employe' => 'employee',
            'gerant' => 'manager',
            'administrateur' => 'admin',
        ];
        $roleCode = $roleCodes[strtolower($this->role_id)] ?? strtolower($this->role_id);
        $roleId = DB::table('roles')->where('code', $roleCode)->value('id');

        if ($roleId !== null) {
            $this->merge(['role_id' => $roleId]);
        }
    }
}