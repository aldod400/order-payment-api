<?php

namespace App\DTOs\Auth;

final readonly class RegisterDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(string $name, string $email, string $password): self
    {
        return new self(
            name: $name,
            email: $email,
            password: $password,
        );
    }
}
