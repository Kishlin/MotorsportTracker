<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentationIfNotExists\SearchTeamPresentationGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchTeamPresentationRepository extends CoreRepository implements SearchTeamPresentationGateway
{
    public function findByTeamAndSeason(UuidValueObject $team, UuidValueObject $season): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('tp.id')
            ->from('team_presentation', 'tp')
            ->where($qb->expr()->eq('tp.team', ':team'))
            ->andWhere($qb->expr()->eq('tp.season', ':season'))
            ->withParam('team', $team->value())
            ->withParam('season', $season->value())
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
