<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateOrUpdateEventSession;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\ExistingEventSessionDTO;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\SearchEventSessionGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
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
    ): ?ExistingEventSessionDTO {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('es.id')
            ->addSelect('es.has_result')
            ->addSelect('es.start_date')
            ->addSelect('es.end_date')
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

        /** @var array<array{id: string, has_result: bool, start_date: ?string, end_date: ?string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return ExistingEventSessionDTO::create(
            new UuidValueObject($result[0]['id']),
            new BoolValueObject($result[0]['has_result']),
            $this->retToDateTimeValueObject($result[0]['end_date']),
        );
    }

    private function retToDateTimeValueObject(?string $ret): NullableDateTimeValueObject
    {
        if (empty($ret)) {
            return new NullableDateTimeValueObject(null);
        }

        return new NullableDateTimeValueObject(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $ret) ?: null,
        );
    }
}
