<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateOrUpdateStandingDriver;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\SaveStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\Standing;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveStandingRepository extends CoreRepository implements SaveStandingGateway
{
    public function save(Standing $standing): void
    {
        $this->persist($standing);
    }

    protected function computeLocation(AggregateRoot $entity): string
    {
        assert($entity instanceof Standing);

        return parent::computeLocation($entity) . '_' . $entity->standingType()->toString();
    }
}
