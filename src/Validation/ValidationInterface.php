<?php

declare(strict_types=1);

namespace App\Validation;

interface ValidationInterface
{
    function check(array $data): array;
}