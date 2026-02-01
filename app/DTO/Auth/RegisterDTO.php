<?php

namespace App\DTO\Auth;

class RegisterDTO
{

   public function __construct(
        public string $name, 
        public string $email, 
        public string $password
   )
   {}

   public static function fromRequest(array $data): self
    {
        return new self(
            name: trim($data['full_name']),
            email: strtolower($data['email_address']),
            password: $data['password']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}