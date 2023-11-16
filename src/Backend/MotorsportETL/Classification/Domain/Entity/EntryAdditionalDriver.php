<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Country;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class EntryAdditionalDriver extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly Entry $entry,
        private readonly Driver $driver,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'entry'  => $this->entry,
            'driver' => $this->driver,
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['entry', 'driver'],
        ];
    }

    /**
     * @param array{name: string, uuid: string, picture: ?string}  $country
     * @param array{name: string, shortCode: string, uuid: string} $driver
     */
    public static function fromData(Entry $entry, array $driver, array $country): self
    {
        return new self(
            $entry,
            Driver::fromData(
                $driver,
                Country::fromData($country),
            ),
        );
    }
}
