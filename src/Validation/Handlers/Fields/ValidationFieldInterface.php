<?php

declare(strict_types=1);

namespace App\Validation\Handlers\Fields;

interface ValidationFieldInterface
{
    static function isValid($value): ?string;
}