<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\FindEntryForSessionAndNumber;

use Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class FindEntryForSessionAndNumberRepository extends CoreRepository implements FindEntryForSessionAndNumberGateway
{
    public function findForSessionAndNumber(UuidValueObject $session, PositiveIntValueObject $number): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('e.id')
            ->from('entry', 'e')
            ->where($qb->expr()->eq('e.session', ':session'))
            ->andWhere($qb->expr()->eq('e.car_number', ':carNumber'))
            ->withParam('carNumber', $number->value())
            ->withParam('session', $session->value())
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
