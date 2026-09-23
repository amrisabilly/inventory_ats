<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'nama' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['manajer', 'admin', 'staff_workshop'])],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($userId)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
