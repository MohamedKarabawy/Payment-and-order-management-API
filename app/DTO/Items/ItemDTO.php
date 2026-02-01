<?php


namespace App\DTO\Items;

class ItemDTO
{

   public function __construct(
        public string $name, 
        public string $description,
        public float $price,
        public int $stock_quantity
        )
   {}

   public static function fromRequest(array $data): self
    {
        return new self(
            name: trim($data['name']),
            description: trim($data['description']) ?? '',
            price: (float) $data['price'],
            stock_quantity: (int) $data['stock_quantity']
        );
    }

    public function toArray(): array
    {
        return [
            'product_name' => $this->name,
            'product_description' => $this->description,
            'product_price' => $this->price,
            'stock_no' => $this->stock_quantity,
        ];
    }
}