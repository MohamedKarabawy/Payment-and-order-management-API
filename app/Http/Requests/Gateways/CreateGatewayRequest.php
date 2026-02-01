<?php

namespace App\Http\Requests\Gateways;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateGatewayRequest extends FormRequest
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
            'merchantId' => 'required|string|max:255',
            'apiKey' => 'required|string|max:255',
            'secretKey' => 'required|string|max:255',
            'environment' => ['required', 'string', Rule::in(['sandbox', 'production'])],
            'status' => ['required', 'string', Rule::in(['enabled', 'disabled'])],
        ];
    }
}
