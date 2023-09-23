<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Season\Domain;

use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeasonDetails;
use Kishlin\Backend\MotorsportETL\Season\Domain\ValueObject\SeriesIdentity;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Season extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly SeriesIdentity $series,
        private readonly SeasonDetails $details,
    ) {
    }

    /**
     * @param array{year: int, uuid: string} $data
     */
    public static function fromData(SeriesIdentity $series, array $data): self
    {
        return new self(
            $series,
            SeasonDetails::fromData($data),
        );
    }

    public function mappedData(): array
    {
        return [
            'series'  => $this->series,
            'details' => $this->details,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['year', 'series'],
        ];
    }
}
