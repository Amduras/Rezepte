<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:' . User::USERNAME_MIN_LENGTH,
                'max:' . User::USERNAME_MAX_LENGTH,
                'unique:users,username',
                'regex:' . User::USERNAME_REGEX,
            ],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(6)],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'              => 'Bitte wähle einen Benutzernamen.',
            'username.min'                   => 'Der Benutzername muss mindestens ' . User::USERNAME_MIN_LENGTH . ' Zeichen lang sein.',
            'username.max'                   => 'Der Benutzername darf maximal ' . User::USERNAME_MAX_LENGTH . ' Zeichen lang sein.',
            'username.unique'                => 'Dieser Benutzername ist bereits vergeben.',
            'username.regex'                 => 'Der Benutzername darf nur Buchstaben, Zahlen, Unterstriche und Bindestriche enthalten.',
            'email.required'                 => 'Bitte gib deine E-Mail-Adresse ein.',
            'email.email'                    => 'Das ist keine gültige E-Mail-Adresse.',
            'email.unique'                   => 'Diese E-Mail-Adresse ist bereits registriert.',
            'password.required'              => 'Bitte wähle ein Passwort.',
            'password.confirmed'             => 'Die Passwort-Bestätigung stimmt nicht überein.',
            'password_confirmation.required' => 'Bitte bestätige dein Passwort.',
        ];
    }
}
