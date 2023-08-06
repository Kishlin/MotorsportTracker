<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\EntryCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Entry extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $session,
        private readonly UuidValueObject $country,
        private readonly UuidValueObject $driver,
        private readonly UuidValueObject $team,
        private readonly PositiveIntValueObject $carNumber,
    ) {
    }

    public static function create(
        UuidValueObject $id,
        UuidValueObject $session,
        UuidValueObject $country,
        UuidValueObject $driver,
        UuidValueObject $team,
        PositiveIntValueObject $carNumber,
    ): self {
        $entry = new self($id, $session, $country, $driver, $team, $carNumber);

        $entry->record(new EntryCreatedDomainEvent($id));

        return $entry;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $session,
        UuidValueObject $country,
        UuidValueObject $driver,
        UuidValueObject $team,
        PositiveIntValueObject $carNumber,
    ): self {
        return new self($id, $session, $country, $driver, $team, $carNumber);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function session(): UuidValueObject
    {
        return $this->session;
    }

    public function country(): UuidValueObject
    {
        return $this->country;
    }

    public function driver(): UuidValueObject
    {
        return $this->driver;
    }

    public function team(): UuidValueObject
    {
        return $this->team;
    }

    public function carNumber(): PositiveIntValueObject
    {
        return $this->carNumber;
    }

    public function mappedData(): array
    {
        return [
            'id'         => $this->id->value(),
            'session'    => $this->session->value(),
            'country'    => $this->country->value(),
            'driver'     => $this->driver->value(),
            'team'       => $this->team->value(),
            'car_number' => $this->carNumber->value(),
        ];
    }
}
