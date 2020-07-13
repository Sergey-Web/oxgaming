<?php

declare(strict_types=1);

namespace App\Validation\Handlers;

use App\Validation\Handlers\Fields\{Email, Phone, UserName, Password, ValidationFieldInterface};
use Exception;

class ValueObject implements ValueObjectInterface
{
    private ValidationFieldInterface $object;

    private array $types = [
        'name' => UserName::class,
        'lastName' => UserName::class,
        'phone' => Phone::class,
        'password' => Password::class,
        'email' => Email::class,
    ];

    public function __construct(string $type)
    {
        if (array_key_exists($type, $this->types) === false) {
            throw new Exception('Invalid validation type of object value');
        }

        $this->object = new $this->types[$type];
    }

    public function check($value): ?string
    {
        return $this->object->isValid($value);
    }
}