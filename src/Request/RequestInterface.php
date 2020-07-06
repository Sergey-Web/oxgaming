<?php

declare(strict_types=1);

namespace App\Request;

use stdClass;

interface RequestInterface
{
    function toObject(): stdClass;

    function toArray(): array;
}