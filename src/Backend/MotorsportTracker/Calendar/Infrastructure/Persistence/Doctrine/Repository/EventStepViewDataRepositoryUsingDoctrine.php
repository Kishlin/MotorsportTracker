<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Infrastructure\Persistence\Doctrine\Repository;

use Exception;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\EventStepViewData;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\CreateViewOnEventStepCreation\EventStepViewDataGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class EventStepViewDataRepositoryUsingDoctrine extends DoctrineRepository implements EventStepViewDataGateway
{
    /**
     * @throws Exception
     */
    public function find(UuidValueObject $eventStepId): ?EventStepViewData
    {
        $query = <<<'SQL'
SELECT c.slug as slug, cp.color as color, cp.icon as icon, e.label as name, st.label as type, v.name as venue, es.date_time as datetime
FROM championships c
LEFT JOIN championship_presentations cp on c.id = cp.championship
LEFT JOIN seasons s on c.id = s.championship
LEFT JOIN events e on s.id = e.season
LEFT JOIN event_steps es on e.id = es.event
LEFT JOIN step_types st on es.type = st.id
LEFT JOIN venues v on e.venue = v.id
WHERE es.id = :eventStepId
LIMIT 1
SQL;

        $params = ['eventStepId' => $eventStepId->value()];

        /** @var array{slug: string, color: string, icon: string, name: string, type: string, venue: string, datetime: string}|false $result */
        $result = $this->entityManager->getConnection()->executeQuery($query, $params)->fetchAssociative();

        if (false === $result) {
            return null;
        }

        return EventStepViewData::fromData($result);
    }
}
