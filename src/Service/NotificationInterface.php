<?php

declare(strict_types=1);

namespace App\Service;

interface NotificationInterface
{
    function view(string $text, string $type): void;
}