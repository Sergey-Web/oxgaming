<?php

declare(strict_types=1);

namespace App\Validation\Handlers;

interface ValueObjectInterface
{
    function check($value): ?string;
}