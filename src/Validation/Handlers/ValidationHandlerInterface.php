<?php

declare(strict_types=1);

namespace App\Validation\Handlers;

interface ValidationHandlerInterface
{
    function valid(array $data): array;
}