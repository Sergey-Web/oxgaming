<?php

declare(strict_types=1);

namespace App\Validation\Handlers;

class UserRegistration implements ValidationHandlerInterface
{
    private ?string $name;

    private ?string $lastName;

    private ?string $phone;

    private ?string $email;

    private ?string $password;

    public function valid(array $data): array
    {
        $this->name = $data['name'];
        $this->lastName = $data['last_name'];
        $this->phone = $data['phone'];
        $this->password = $data['password'];
        $this->email = $data['email'];

        $errors = $this->checkRequiredFields();

        if (empty($errors)) {
            $errors = $this->validationFields();
        }

        return $errors;
    }

    private function checkRequiredFields(): array
    {
        $errors = [];

        if ($this->name === null) {
            $errors[] = ['name' => 'Field name is not specified'];
        }

        if ($this->phone === null) {
            $errors[] = ['phone' => 'Field phone is not specified'];;
        }

        if ($this->password === null) {
            $errors[] = ['password' => 'Field password is not specified'];;
        }


        return $errors;
    }

    private function validationFields(): array
    {
        $errors = [];
        foreach($this as $key => $item) {
            if ($item !== null) {
                $error = (new ValueObject($key))->check($item);
                if ($error !== null) {
                    $errors[$key] = $error;
                }
            }
        }

        return $errors;
    }
}