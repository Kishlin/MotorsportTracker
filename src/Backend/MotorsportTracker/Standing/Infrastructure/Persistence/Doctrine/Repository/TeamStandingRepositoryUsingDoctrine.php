<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Standing\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Standing\Domain\Entity\TeamStanding;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\Gateway\TeamStandingGateway;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingEventId;
use Kishlin\Backend\MotorsportTracker\Standing\Domain\ValueObject\TeamStandingTeamId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class TeamStandingRepositoryUsingDoctrine extends DoctrineRepository implements TeamStandingGateway
{
    public function save(TeamStanding $teamStanding): void
    {
        $this->persist($teamStanding);
    }

    public function find(TeamStandingTeamId $teamId, TeamStandingEventId $eventId): ?TeamStanding
    {
        return $this->entityManager->getRepository(TeamStanding::class)->findOneBy([
            'eventId' => $eventId,
            'teamId'  => $teamId,
        ]);
    }
}
