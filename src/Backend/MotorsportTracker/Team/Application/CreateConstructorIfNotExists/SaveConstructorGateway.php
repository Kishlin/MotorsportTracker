<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Constructor;

interface SaveConstructorGateway
{
    public function save(Constructor $constructor): void;
}
