<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Repository;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\LocationComputer;

abstract readonly class WriteRepository
{
    public function __construct(
        private LocationComputer $locationComputer,
        private Connection $connection,
    ) {
    }

    protected function persist(Entity $entity): void
    {
        $location = $this->locationComputer->computeLocation($entity);
        $data     = $entity->mappedData();

        $this->connection->insert($location, $data);
    }
}
