<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Application\ScrapCalendar;

use Generator;
use Kishlin\Backend\MotorsportETL\Calendar\Domain\Entity\Event;
use Kishlin\Backend\MotorsportETL\Calendar\Domain\Entity\EventSession;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;
use Kishlin\Backend\Shared\Domain\Entity\Entity;

final readonly class CalendarTransformer
{
    public function __construct(
        private JsonableStringTransformer $jsonableStringParser,
    ) {
    }

    /**
     * @return Generator<Entity>
     */
    public function transform(SeasonIdentity $season, mixed $extractorResponse): Generator
    {
        /**
         * @var array{
         *     events: array<int, array{
         *         uuid: string,
         *         name: string,
         *         shortName: string,
         *         shortCode: string,
         *         status: string,
         *         startDate: ?int,
         *         startTimeUtc: ?int,
         *         endDate: ?int,
         *         endTimeUtc: ?int,
         *         venue: array{name: string, uuid: string, shortName: string, shortCode: string, picture: string},
         *         country: array{name: string, uuid: string, picture: string},
         *         sessions: array<array{
         *             uuid: string,
         *             name: string,
         *             shortName: string,
         *             shortCode: string,
         *             status: string,
         *             hasResults: bool,
         *             startTime: ?int,
         *             startTimeUtc: ?int,
         *             endTime: ?int,
         *             endTimeUtc: ?int,
         *         }>
         *     }>
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($content['events'] as $key => $event) {
            $eventEntity = Event::fromData($season->id(), $key, $event);

            yield $eventEntity;

            foreach ($event['sessions'] as $session) {
                yield EventSession::fromData($eventEntity, $session);
            }
        }
    }
}
