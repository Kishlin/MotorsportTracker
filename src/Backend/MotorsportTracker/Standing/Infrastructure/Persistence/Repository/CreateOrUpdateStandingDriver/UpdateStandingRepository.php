<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Repository\CreateOrUpdateStandingDriver;

use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\StandingUpdateFailureEvent;
use Kishlin\Backend\MotorsportTracker\Standing\Application\CreateOrUpdateStanding\UpdateStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Enum\StandingType;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class UpdateStandingRepository extends CoreRepository implements UpdateStandingGateway
{
    public function update(
        UuidValueObject $record,
        StandingType $standingType,
        StrictlyPositiveIntValueObject $position,
        FloatValueObject $points,
    ): bool {
        $qb = $this->connection->createQueryBuilder();

        $table = 'standing_' . $standingType->toString();

        $qb->update($table, 'sd')
            ->set('position', ':position')
            ->set('points', ':points')
            ->where($qb->expr()->eq('sd.id', ':record'))
            ->withParam('record', $record->value())
            ->withParam('position', $position->value())
            ->withParam('points', $points->value())
        ;

        $result = $this->connection->execute($qb->buildQuery());

        return $result->isOk();
    }
}
