<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Override;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'delivery_address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    #[Override]
    // traduis un role textuel en role numérique
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
