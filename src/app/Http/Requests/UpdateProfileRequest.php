<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return [
            'username' => [
                'required',
                'string',
                'min:' . User::USERNAME_MIN_LENGTH,
                'max:' . User::USERNAME_MAX_LENGTH,
                'unique:users,username,' . $user->id,
                'regex:' . User::USERNAME_REGEX,
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(6),
            ],
            'password_confirmation' => [
                'nullable',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'              => 'Bitte gib einen Benutzernamen ein.',
            'username.min'                   => 'Der Benutzername muss mindestens ' . User::USERNAME_MIN_LENGTH . ' Zeichen lang sein.',
            'username.max'                   => 'Der Benutzername darf maximal ' . User::USERNAME_MAX_LENGTH . ' Zeichen lang sein.',
            'username.unique'                => 'Dieser Benutzername ist bereits vergeben.',
            'username.regex'                 => 'Der Benutzername darf nur Buchstaben, Zahlen, Unterstriche und Bindestriche enthalten.',
            'email.required'                 => 'Bitte gib eine E-Mail-Adresse ein.',
            'email.email'                    => 'Das ist keine gültige E-Mail-Adresse.',
            'email.unique'                   => 'Diese E-Mail-Adresse ist bereits registriert.',
            'password.confirmed'             => 'Die Passwort-Bestätigung stimmt nicht überein.',
            'password.min'                   => 'Das Passwort muss mindestens 6 Zeichen lang sein.',
        ];
    }
}
