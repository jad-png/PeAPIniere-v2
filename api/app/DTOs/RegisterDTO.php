<?php

namespace App\DTOs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterDTO
{
    public function __construct(
        public readonly string $fullname,
        public readonly string $email,
        public readonly string $password,
        public readonly string $password_confirmation,
    ) {}

    public static function fromRequest(array $data)
    {
        $validator = Validator::make($data, [
            "fullname" => ['required', 'max:100'],
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "password" => ['required', 'confirmed']
        ]);

        if ($validator->fails()) throw new ValidationException($validator);
        return new self(
            $data["fullname"],
            $data["email"],
            $data["password"],
            $data["password_confirmation"]
        );
    }

    public function toArray()
    {
        return [
            "fullname" => $this->fullname,
            "email" => $this->email,
            "password" => $this->password,
        ];
    }
}
