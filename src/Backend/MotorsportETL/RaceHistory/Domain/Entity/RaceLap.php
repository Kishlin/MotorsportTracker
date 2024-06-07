<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Domain\Entity;

use Kishlin\Backend\MotorsportETL\RaceHistory\Domain\ValueObject\RaceLapDetails;
use Kishlin\Backend\MotorsportETL\RaceHistory\Domain\ValueObject\RaceLapGaps;
use Kishlin\Backend\MotorsportETL\RaceHistory\Domain\ValueObject\RaceLapTyreDetails;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class RaceLap extends Entity implements GuardedAgainstDoubles
{
    public function __construct(
        private readonly string $entry,
        private readonly RaceLapDetails $details,
        private readonly RaceLapGaps $gaps,
        private readonly RaceLapTyreDetails $tyreDetails,
    ) {}

    public function mappedData(): array
    {
        return [
            'entry'   => $this->entry,
            'details' => $this->details,
            'gaps'    => $this->gaps,
            'tyres'   => $this->tyreDetails,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::UPDATE;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['entry', 'lap'],
        ];
    }

    /**
     * @param array{
     *     position: int,
     *     pit: bool,
     *     time: int,
     *     gap: array{timeToLead: ?int, lapsToLead: ?int, timeToNext: ?int, lapsToNext: ?int},
     *     tyreDetail: array{type: string, wear: string, laps: int}[],
     * } $data
     */
    public static function fromData(string $entry, int $lap, array $data): self
    {
        return new self(
            $entry,
            RaceLapDetails::fromData($lap, $data),
            RaceLapGaps::fromData($data['gap']),
            RaceLapTyreDetails::fromData($data['tyreDetail']),
        );
    }
}
