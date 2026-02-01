<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
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
            'status' => ['sometimes', 'required', 'string', Rule::in(['pending', 'completed', 'canceled'])],
            'currency' => ['sometimes','required', 'string', Rule::in(['USD', 'EUR', 'EGP'])],
            'customer_name' => 'sometimes|required|string',
            'customer_email' => 'sometimes|required|email',
            'customer_address' => 'sometimes|required|string',
            'customer_city' => 'sometimes|required|string',
            'customer_country' => 'sometimes|required|string',
            'customer_phone' => 'nullable|string',
            'customer_zip_code' => 'nullable|string',
            'order_items' => 'sometimes|required|array|min:1',
            'order_items.*.item_id' => 'sometimes|required|integer|exists:pg_items,id',
            'order_items.*.quantity' => 'sometimes|required|integer|min:1',
        ];
    }
}
