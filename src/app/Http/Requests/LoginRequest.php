<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Bitte gib deinen Benutzernamen ein.',
            'username.max'      => 'Der Benutzername darf maximal 50 Zeichen lang sein.',
            'password.required' => 'Bitte gib dein Passwort ein.',
        ];
    }
}
