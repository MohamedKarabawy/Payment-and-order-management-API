<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
            'paymentMethod' => ['required', 'string', Rule::in(['paypal', 'stripe', 'visa'])],
            'orderId' => 'required|integer|max:50'
        ];
    }
}
