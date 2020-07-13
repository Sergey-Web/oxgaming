<?php

declare(strict_types=1);

namespace App\Validation\Handlers\Fields;

class Email implements ValidationFieldInterface
{
    private const MESSAGE = 'Invalid email input';

    public static function isValid($value): ?string
    {
        $error = null;
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
           $error = static::MESSAGE;
        }

        return $error;
    }
}