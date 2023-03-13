<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\SearchEventSessionGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchEventSessionRepositoryUsingDoctrine extends CoreRepository implements SearchEventSessionGateway
{
    public function search(UuidValueObject $event, UuidValueObject $type): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('es.id')
            ->from('event_session', 'es')
            ->where('es.event = :event')
            ->andWhere('es.type = :type')
            ->withParam('event', $event->value())
            ->withParam('type', $type->value())
            ->buildQuery()
        ;

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
