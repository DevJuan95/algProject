<?php

namespace App\Http\Requests;

use App\Enums\IngredientName;
use App\Rules\IngredientExists;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngredientsDeliveryRequest extends FormRequest
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
            //
            'orderId' => 'required|integer',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.name' => ['required','string', Rule::enum(IngredientName::class)],
            'ingredients.*.quantity' => 'required|integer|min:1',
        ];
    }
}
