<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
            'status' => ['required', 'string', Rule::in(['pending', 'completed', 'canceled'])],
            'currency' => ['required', 'string', Rule::in(['USD', 'EUR', 'EGP'])],
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_address' => 'required|string',
            'customer_city' => 'required|string',
            'customer_country' => 'required|string',
            'customer_phone' => 'nullable|string',
            'customer_zip_code' => 'nullable|string',
            'order_items' => 'required|array|min:1',
            'order_items.*.item_id' => 'required|integer|exists:pg_items,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
