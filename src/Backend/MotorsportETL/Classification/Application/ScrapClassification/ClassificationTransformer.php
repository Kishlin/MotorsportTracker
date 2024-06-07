<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification;

use Generator;
use Kishlin\Backend\MotorsportETL\Classification\Domain\Entity\Classification;
use Kishlin\Backend\MotorsportETL\Classification\Domain\Entity\Entry;
use Kishlin\Backend\MotorsportETL\Classification\Domain\Entity\EntryAdditionalDriver;
use Kishlin\Backend\MotorsportETL\Classification\Domain\Entity\Retirement;
use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringTransformer;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;
use Kishlin\Backend\Shared\Domain\Entity\Entity;

final class ClassificationTransformer
{
    /** @var array<string, Entry> */
    private array $entryForDriverIdCache;

    public function __construct(
        private readonly JsonableStringTransformer $jsonableStringParser,
    ) {}

    /**
     * @return Generator<Entity>
     */
    public function transform(SessionIdentity $session, mixed $extractorResponse): Generator
    {
        $this->entryForDriverIdCache = [];

        /**
         * @var array{
         *     details: array{
         *         finishPosition: int,
         *         gridPosition: ?int,
         *         carNumber: string,
         *         drivers: array<array{
         *             name: string,
         *             firstName: string,
         *             lastName: string,
         *             shortCode: string,
         *             colour: null|string,
         *             uuid: string,
         *             picture: string,
         *         }>,
         *         team: array{name: string, uuid: string, colour: string, picture: string, carIcon: string},
         *         nationality: array{name: string, uuid: string, picture: string},
         *         laps: int,
         *         points: float,
         *         time: float,
         *         classifiedStatus: ?string,
         *         avgLapSpeed: float,
         *         fastestLapTime: ?float,
         *         gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
         *         best: array{lap: ?int, time: ?float, fastest: ?bool, speed: ?float}
         *     }[],
         *     retirements: array<array{
         *         driver: array{
         *             name: string,
         *             firstName: string,
         *             lastName: string,
         *             shortCode: string,
         *             colour: null|string,
         *             uuid: string,
         *             picture: string,
         *         },
         *         carNumber: string,
         *         reason: string,
         *         type: string,
         *         dns: bool,
         *         lap: int,
         *         details: null,
         *     }>
         * } $content
         */
        $content = $this->jsonableStringParser->transform($extractorResponse);

        foreach ($content['details'] as $classification) {
            if (empty($classification['drivers'])) {
                // TODO: Allow creation of entries without drivers
                continue;
            }

            $entry = Entry::fromData(
                $session->id(),
                $session->season(),
                $classification['nationality'],
                $classification['drivers'][0],
                $classification['team'],
                $classification['carNumber'],
            );

            $this->storeEntryForCarNumber($classification['carNumber'], $entry);

            yield $entry;

            for ($i = 1, $max = count($classification['drivers']); $i < $max; ++$i) {
                yield EntryAdditionalDriver::fromData(
                    $entry,
                    $classification['drivers'][$i],
                    $classification['nationality'],
                );
            }

            yield Classification::fromData(
                $entry,
                $classification,
            );
        }

        foreach ($content['retirements'] as $retirement) {
            $entry = $this->retrieveEntryForCarNumber($retirement['carNumber']);
            if (null === $entry) {
                continue;
            }

            yield Retirement::fromData($entry, $retirement);
        }
    }

    private function storeEntryForCarNumber(string $carNumber, Entry $entry): void
    {
        $this->entryForDriverIdCache[$carNumber] = $entry;
    }

    private function retrieveEntryForCarNumber(string $carNumber): ?Entry
    {
        if (false === array_key_exists($carNumber, $this->entryForDriverIdCache)) {
            return null;
        }

        return $this->entryForDriverIdCache[$carNumber];
    }
}
