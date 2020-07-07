<?php

declare(strict_types=1);

namespace Route\Handlers;

use Doctrine\ORM\EntityManagerInterface;

interface RouteInterface
{
    function getAction(EntityManagerInterface $entityManager);
}