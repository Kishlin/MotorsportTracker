<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\SearchTeamGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchTeamRepository extends CoreRepository implements SearchTeamGateway
{
    public function findForRef(NullableUuidValueObject $ref): ?UuidValueObject
    {
        if (null === $ref->value()) {
            return null;
        }

        $qb = $this->connection->createQueryBuilder();

        $qb->select('t.id')
            ->from('team', 't')
            ->where($qb->expr()->eq('t.ref', ':ref'))
            ->withParam('ref', $ref->value())
        ;

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
