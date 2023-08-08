<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\SearchEventSessionGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchEventSessionRepository extends CoreRepository implements SearchEventSessionGateway
{
    public function search(
        UuidValueObject $event,
        UuidValueObject $typeId,
        NullableDateTimeValueObject $startDate,
    ): ?UuidValueObject {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('es.id')
            ->from('event_session', 'es')
            ->where($qb->expr()->eq('es.event', ':event'))
            ->andWhere($qb->expr()->eq('es.type', ':type'))
            ->withParam('event', $event->value())
            ->withParam('type', $typeId->value())
        ;

        if (null !== $startDate->value()) {
            $qb
                ->andWhere($qb->expr()->eq('es.start_date', ':startDate'))
                ->withParam('startDate', $startDate->value()->format('Y-m-d H:i:s'))
            ;
        }

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
