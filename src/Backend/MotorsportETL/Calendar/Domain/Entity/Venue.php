<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Calendar\Domain\ValueObject\VenueDetails;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class Venue extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly VenueDetails $venueDetails,
        private readonly Country $country,
    ) {}

    public function mappedData(): array
    {
        return [
            'details' => $this->venueDetails,
            'country' => $this->country,
        ];
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['name'],
            ['ref'],
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    /**
     * @param array{name: string, uuid: string, shortName: string, shortCode: string, picture: string} $data
     */
    public static function fromData(array $data, Country $country): self
    {
        return new self(
            VenueDetails::fromData($data),
            $country,
        );
    }
}
