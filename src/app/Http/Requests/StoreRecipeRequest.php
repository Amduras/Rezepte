<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RecipeDifficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Rezept-Daten
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:65535'],
            'prep_time'   => ['nullable', 'integer', 'min:0', 'max:1440'],
            'cook_time'   => ['nullable', 'integer', 'min:0', 'max:1440'],
            'servings'    => ['nullable', 'integer', 'min:1', 'max:100'],
            'difficulty'  => ['required', Rule::enum(RecipeDifficulty::class)],
            'tags'        => ['nullable', 'string', 'max:500'],

            // Bilder
            'images'      => ['nullable', 'array', 'max:5'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            // Zutaten
            'ingredients'                => ['nullable', 'array'],
            'ingredients.*.name'         => ['required_with:ingredients', 'string', 'max:150'],
            'ingredients.*.quantity'     => ['nullable', 'string', 'max:50'],
            'ingredients.*.unit'         => ['nullable', 'string', 'max:30'],
            'ingredients.*.note'         => ['nullable', 'string', 'max:65535'],

            // Schritte
            'steps'                      => ['nullable', 'array'],
            'steps.*.instruction'        => ['required_with:steps', 'string', 'max:65535'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'              => 'Bitte gib dem Rezept einen Namen.',
            'difficulty.required'         => 'Bitte wähle einen Schwierigkeitsgrad.',
            'difficulty.enum'             => 'Ungültiger Schwierigkeitsgrad.',
            'images.*.image'              => 'Die Datei muss ein Bild sein.',
            'images.*.max'                => 'Das Bild darf maximal 5MB groß sein.',
            'ingredients.*.name.required_with' => 'Bitte gib den Namen der Zutat an.',
            'steps.*.instruction.required_with' => 'Bitte beschreibe den Zubereitungsschritt.',
        ];
    }
}
