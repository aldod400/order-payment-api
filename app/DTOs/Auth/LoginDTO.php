<?php

namespace App\DTOs\Auth;

final readonly class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(string $email, string $password): self
    {
        return new self(
            email: $email,
            password: $password,
        );
    }
}
