<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Calendar\Domain\ValueObject\EventDetails;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Event extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly string $season,
        private readonly EventDetails $eventDetails,
        private readonly Venue $venue,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'season'  => $this->season,
            'details' => $this->eventDetails,
            'venue'   => $this->venue,
        ];
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['season', 'name', 'index'],
            ['season', 'index'],
            ['ref'],
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startDate: ?int,
     *     startTimeUtc: ?int,
     *     endDate: ?int,
     *     endTimeUtc: ?int,
     *     venue: array{name: string, uuid: string, shortName: string, shortCode: string, picture: string},
     *     country: array{name: string, uuid: string, picture: string},
     *     sessions: array<array{
     *         uuid: string,
     *         name: string,
     *         shortName: string,
     *         shortCode: string,
     *         status: string,
     *         hasResults: bool,
     *         startTime: ?int,
     *         startTimeUtc: ?int,
     *         endTime: ?int,
     *         endTimeUtc: ?int,
     *     }>
     * } $event
     */
    public static function fromData(string $season, int $index, array $event): self
    {
        $country = Country::fromData($event['country']);

        return new self(
            $season,
            EventDetails::fromData($index, $event),
            Venue::fromData($event['venue'], $country),
        );
    }
}
