<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory;

use Generator;
use Kishlin\Backend\MotorsportETL\RaceHistory\Domain\Entity\RaceLap;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Shared\Domain\Entity\Entity;

final readonly class RaceHistoryTransformer
{
    public function __construct(
        private JsonableStringTransformer $jsonableStringParser,
        private EventDispatcher $eventDispatcher,
        private EntryGateway $entryGateway,
    ) {}

    /**
     * @return Generator<Entity>
     */
    public function transform(SessionIdentity $session, mixed $extractorResponse): Generator
    {
        /**
         * @var array{
         *     entries: array{
         *         uuid: string,
         *         carNumber:string,
         *         driver: array{name: string, uuid: string, shortCode: string, colour: null|string, picture: string},
         *     }[],
         *     laps: array{
         *         lap: int,
         *         carPosition: array{
         *             entryUuid: string,
         *             position: int,
         *             pit: bool,
         *             time: int,
         *             gap: array{timeToLead: ?int, lapsToLead: ?int, timeToNext: ?int, lapsToNext: ?int},
         *             tyreDetail: array{type: string, wear: string, laps: int}[],
         *         }[],
         *     }[],
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        $entries = [];
        $skipped = [];

        foreach ($content['entries'] as $entry) {
            $entries[$entry['uuid']] = $entry['carNumber'];

            $stored = $this->entryGateway->find($session, $entry['carNumber']);

            if (null === $stored) {
                $skipped[$entry['uuid']] = $entry;
            } else {
                $entries[$entry['uuid']] = $stored;
            }
        }

        foreach ($content['laps'] as $lap) {
            $lapKey = $lap['lap'];

            foreach ($lap['carPosition'] as $key => $carPosition) {
                // Some positions are duplicated, and the first occurrence has no tyre details
                if (empty($carPosition['tyreDetail'])
                    && array_key_exists($key + 1, $lap['carPosition'])
                    && $lap['carPosition'][$key + 1]['entryUuid'] === $lap['carPosition'][$key]['entryUuid']) {
                    continue;
                }

                $entryUuid = $carPosition['entryUuid'];

                if (array_key_exists($entryUuid, $skipped)) {
                    $this->eventDispatcher->dispatch(
                        RaceLapForSkippedEntryEvent::fromScalars(
                            $session->id(),
                            $skipped[$entryUuid]['carNumber'],
                            $carPosition,
                        ),
                    );

                    continue;
                }

                yield RaceLap::fromData(
                    $entries[$entryUuid],
                    $lapKey,
                    $carPosition,
                );
            }
        }
    }
}
