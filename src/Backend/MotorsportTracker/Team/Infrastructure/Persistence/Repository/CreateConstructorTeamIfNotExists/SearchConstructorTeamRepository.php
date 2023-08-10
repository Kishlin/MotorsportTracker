<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateConstructorTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateConstructorTeamIfNotExists\SearchConstructorTeamGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SearchConstructorTeamRepository extends CoreRepository implements SearchConstructorTeamGateway
{
    public function has(UuidValueObject $constructor, UuidValueObject $team): bool
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('1')
            ->from('constructor_team', 'ct')
            ->andWhere($qb->expr()->eq('ct.constructor', ':constructor'))
            ->andWhere($qb->expr()->eq('ct.team', ':team'))
            ->withParam('constructor', $constructor->value())
            ->withParam('team', $team->value())
            ->limit(1)
        ;

        /** @var null|int $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchOne();

        return 1 === $result;
    }
}
