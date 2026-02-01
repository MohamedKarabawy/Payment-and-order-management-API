<?php

namespace App\Http\Requests\Simulator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
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
            'merchant_id' => 'required|string|max:128',
            'secret_key' => 'required|string|max:128',
            'environment' => ['required', Rule::in(['sandbox', 'production'])],
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'idempotency_key' => 'nullable|string|max:255',
            'internal_reference' => 'nullable|string|max:255',
            'callback_url' => 'required|url'
        ];
    }
}
