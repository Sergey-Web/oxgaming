<?php

declare(strict_types=1);

namespace App\Service;

class NotificationService implements NotificationInterface
{
    private array $types = [
        'success' => "\033[32m",
        'fail' => "\033[31m",
    ];

    public function view(string $text, string $type = 'success'): void
    {
        if (array_key_exists($type, $this->types) === false) {
            throw new \Exception('Invalid notification type');
        }

        echo $this->types[$type] . $text . "\n";
    }
}