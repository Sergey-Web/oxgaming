<?php

declare(strict_types=1);

namespace Route;

use Route\Handlers\RouteInterface;

interface RouterInterface
{
    function getRoute(): RouteInterface;
}