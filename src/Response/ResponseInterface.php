<?php

declare(strict_types=1);

namespace App\Response;

interface ResponseInterface
{
    function json(): string;
}