<?php

declare(strict_types=1);

namespace App\Validation\Handlers\Fields;

class Phone implements ValidationFieldInterface
{
    private const MESSAGE = 'The phone field should contain 12 digits';

    public static function isValid($value): ?string
    {
        $error = null;
        if (is_numeric($value) && strlen($value) !== 12) {
            $error = static::MESSAGE;
        }

        return $error;
    }
}