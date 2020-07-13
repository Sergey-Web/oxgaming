<?php

declare(strict_types=1);

namespace App\Validation\Handlers\Fields;

class UserName implements ValidationFieldInterface
{
    private const MESSAGE = 'The name field must contain min 3 characters and a maximum of 50';

    public static function isValid($value): ?string
    {
        $error = null;
        if (!(mb_strlen($value) >= 3 && mb_strlen($value) < 50)) {
            $error = static::MESSAGE;
        }

        return $error;
    }
}