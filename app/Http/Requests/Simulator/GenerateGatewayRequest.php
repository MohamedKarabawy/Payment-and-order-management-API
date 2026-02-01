<?php

namespace App\Http\Requests\Simulator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateGatewayRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gateway' => ['required', Rule::in(['paypal', 'stripe', 'visa'])],
            'environment' => ['required', Rule::in(['sandbox', 'production'])]
        ];
    }
}
