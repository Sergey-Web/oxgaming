<?php

declare(strict_types=1);

namespace App\Validation\Handlers;

class UserLogin implements ValidationHandlerInterface
{
    private ?string $name;

    private ?string $phone;

    public  function valid(array $data): array
    {
        $this->phone = $data['phone'];
        $this->name = $data['password'];

        $errors = $this->checkRequiredFields();

        return $errors;
    }

    private function checkRequiredFields(): array
    {
        $errors = [];

        if ($this->name === null) {
            $errors[] = ['name' => 'Field "name" is not specified'];
        }

        if ($this->phone === null) {
            $errors[] = ['phone' => 'Field "phone" is not specified'];;
        }

        return $errors;
    }
}