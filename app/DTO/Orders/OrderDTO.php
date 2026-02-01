<?php


namespace App\DTO\Orders;

use App\Models\Order;

class OrderDTO
{

    public function __construct(
        public ?string $status,
        public ?string $currency,
        public ?int $userId,
        public ?string $customer_name,
        public ?string $customer_email,
        public ?string $customer_address,
        public ?string $customer_city,
        public ?string $customer_country,
        public ?string $customer_phone,
        public ?string $customer_zip_code,
        public ?float $total_amount = 0.0
    ) {
    }

    public static function fromRequest(array $data, ?Order $order = null): self
    {
        return new self(
            status: trim($data['status'] ?? null),
            currency: trim($data['currency'] ?? null),
            userId: (int) auth()->user()->id,
            customer_name: trim($data['customer_name'] ?? null),
            customer_email: strtolower($data['customer_email'] ?? null),
            customer_address: trim($data['customer_address'] ?? null),
            customer_city: trim($data['customer_city'] ?? null),
            customer_country: trim($data['customer_country'] ?? null),
            customer_phone: trim($data['customer_phone'] ?? 'empty'),
            customer_zip_code: trim($data['customer_zip_code'] ?? 'empty'),
            total_amount: (float) $order?->total_price?? 0.0
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'status' => $this->status,
            'currency' => $this->currency,
            'user_id' => $this->userId,
            'customer_name' => $this->customer_name,
            'billing_email' => $this->customer_email,
            'billing_address' => $this->customer_address,
            'billing_city' => $this->customer_city,
            'billing_country' => $this->customer_country,
            'billing_phone' => $this->customer_phone,
            'billing_zip_code' => $this->customer_zip_code,
            'total_price' => $this->total_amount,
        ], fn($v) => $v !== "");
    }
}