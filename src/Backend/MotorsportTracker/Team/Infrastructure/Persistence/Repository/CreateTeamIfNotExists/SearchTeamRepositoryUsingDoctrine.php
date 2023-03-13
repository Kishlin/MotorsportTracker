<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamIfNotExists\SearchTeamGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchTeamRepositoryUsingDoctrine extends CoreRepository implements SearchTeamGateway
{
    public function findByNameOrRef(StringValueObject $name, NullableUuidValueObject $ref): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('t.id')
            ->from('team', 't')
        ;

        if (null !== $ref->value()) {
            $qb->where($qb->expr()->eq('t.ref', ':ref'))->withParam('ref', $ref->value());
        } else {
            $qb->where($qb->expr()->eq('t.name', ':name'))->withParam('name', $name->value());
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
