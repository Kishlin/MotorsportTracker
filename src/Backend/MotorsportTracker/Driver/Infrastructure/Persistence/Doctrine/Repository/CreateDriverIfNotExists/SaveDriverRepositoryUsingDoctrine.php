<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriverIfNotExists;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriverIfNotExists\SaveDriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepositoryLegacy;

final class SaveDriverRepositoryUsingDoctrine extends CoreRepositoryLegacy implements SaveDriverGateway
{
    public function save(Driver $driver): void
    {
        parent::persist($driver);
    }
}
