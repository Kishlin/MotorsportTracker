<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Infrastructure\Persistence\Doctrine\Repository\CreateDriver;

use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\SaveDriverGateway;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveDriverRepositoryUsingDoctrine extends CoreRepository implements SaveDriverGateway
{
    public function save(Driver $driver): void
    {
        parent::persist($driver);
    }
}
