<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Classification\Domain\ValueObject\ClassificationBestLap;
use Kishlin\Backend\MotorsportETL\Classification\Domain\ValueObject\ClassificationDetails;
use Kishlin\Backend\MotorsportETL\Classification\Domain\ValueObject\ClassificationGaps;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Classification extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly Entry $entry,
        private readonly ClassificationDetails $classificationDetails,
        private readonly ClassificationGaps $classificationGaps,
        private readonly ClassificationBestLap $classificationBestLap,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'entry'   => $this->entry,
            'details' => $this->classificationDetails,
            'gaps'    => $this->classificationGaps,
            'bestLap' => $this->classificationBestLap,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::UPDATE;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['entry'],
        ];
    }

    /**
     * @param array{
     *     finishPosition: int,
     *     gridPosition: ?int,
     *     laps: int,
     *     points: float,
     *     time: float,
     *     classifiedStatus: ?string,
     *     avgLapSpeed: float,
     *     fastestLapTime: ?float,
     *     gap: array{timeToLead: float, timeToNext: float, lapsToLead: int, lapsToNext: int},
     *     best: array{lap: ?int, time: ?float, fastest: ?bool, speed: ?float}
     * } $data
     */
    public static function fromData(
        Entry $entry,
        array $data,
    ): self {
        return new self(
            $entry,
            ClassificationDetails::fromData($data),
            ClassificationGaps::fromData($data['gap']),
            ClassificationBestLap::fromData($data['best']),
        );
    }
}
