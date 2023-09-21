<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository;

use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class EntityRepository extends WriteRepository implements CoreRepositoryInterface
{
    public function save(Entity $entity): void
    {
        $this->persist($entity);
    }
}
