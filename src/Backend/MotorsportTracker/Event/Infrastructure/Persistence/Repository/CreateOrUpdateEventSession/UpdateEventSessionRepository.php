<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateOrUpdateEventSession;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\UpdateEventSessionGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class UpdateEventSessionRepository extends CoreRepository implements UpdateEventSessionGateway
{
    public function update(
        UuidValueObject $sessionId,
        NullableDateTimeValueObject $endDate,
        BoolValueObject $hasResult,
    ): bool {
        $qb = $this->connection->createQueryBuilder();

        $table = 'event_session';

        $qb->update($table, 'es')
            ->set('end_date', ':endDate')
            ->set('has_result', ':hasResult')
            ->where($qb->expr()->eq('es.id', ':record'))
            ->withParam('record', $sessionId->value())
            ->withParam('endDate', $endDate->value()?->format('Y-m-d H:i:s'))
            ->withParam('hasResult', $hasResult->value() ? 1 : 0)
        ;

        $result = $this->connection->execute($qb->buildQuery());

        return $result->isOk();
    }
}
