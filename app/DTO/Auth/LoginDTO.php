<?php


namespace App\DTO\Auth;

class LoginDTO
{

   public function __construct(
        public string $email, 
        public string $password
        )
   {}

   public static function fromRequest(array $data): self
    {
        return new self(
            email: strtolower($data['email_address']),
            password: $data['password']
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}