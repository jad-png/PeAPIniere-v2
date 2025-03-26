<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginDTO
{
    public function __construct(public readonly string $email, public readonly string $password) {}

    public static function fromRequest(array $data): self
    {
        $validator = Validator::make($data, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return new self($data['email'], $data['password']);
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
