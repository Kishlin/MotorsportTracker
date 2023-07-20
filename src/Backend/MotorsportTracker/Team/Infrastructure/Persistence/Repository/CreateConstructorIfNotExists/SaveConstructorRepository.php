<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateConstructorIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorIfNotExists\SaveConstructorGateway;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Constructor;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveConstructorRepository extends CoreRepository implements SaveConstructorGateway
{
    public function save(Constructor $constructor): void
    {
        parent::persist($constructor);
    }
}
