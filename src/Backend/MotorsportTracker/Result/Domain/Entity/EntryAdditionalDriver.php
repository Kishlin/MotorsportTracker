<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\EntryAdditionalDriverCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class EntryAdditionalDriver extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $entry,
        private readonly UuidValueObject $driver,
    ) {
    }

    public static function create(UuidValueObject $id, UuidValueObject $entry, UuidValueObject $driver): self
    {
        $entry = new self($id, $entry, $driver);

        $entry->record(new EntryAdditionalDriverCreatedDomainEvent($id));

        return $entry;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(UuidValueObject $id, UuidValueObject $entry, UuidValueObject $driver): self
    {
        return new self($id, $entry, $driver);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function entry(): UuidValueObject
    {
        return $this->entry;
    }

    public function driver(): UuidValueObject
    {
        return $this->driver;
    }

    public function mappedData(): array
    {
        return [
            'id'     => $this->id->value(),
            'entry'  => $this->entry->value(),
            'driver' => $this->driver->value(),
        ];
    }
}
