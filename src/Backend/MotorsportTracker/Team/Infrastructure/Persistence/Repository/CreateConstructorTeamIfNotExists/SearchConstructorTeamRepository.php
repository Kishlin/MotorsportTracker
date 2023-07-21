<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists\SearchConstructorTeamGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchConstructorTeamRepository extends CoreRepository implements SearchConstructorTeamGateway
{
    public function find(UuidValueObject $constructor, UuidValueObject $team): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('ct.id')
            ->from('constructor_team', 'ct')
            ->andWhere($qb->expr()->eq('ct.constructor', ':constructor'))
            ->andWhere($qb->expr()->eq('ct.team', ':team'))
            ->withParam('constructor', $constructor->value())
            ->withParam('team', $team->value())
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
