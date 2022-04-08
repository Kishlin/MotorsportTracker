<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverFirstname;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;

final class Driver extends AggregateRoot
{
    public function __construct(
        private DriverId $id,
        private DriverFirstname $firstname,
        private DriverName $name,
    ) {
    }

    public static function create(DriverId $id, DriverFirstname $firstname, DriverName $name): self
    {
        $driver = new self($id, $firstname, $name);

        $driver->record(new DriverCreatedDomainEvent($id));

        return $driver;
    }

    /**
     * @internal Only use to get a test object.
     */
    public static function instance(DriverId $id, DriverFirstname $firstname, DriverName $name): self
    {
        return new self($id, $firstname, $name);
    }

    public function id(): DriverId
    {
        return $this->id;
    }

    public function firstname(): DriverFirstname
    {
        return $this->firstname;
    }

    public function name(): DriverName
    {
        return $this->name;
    }
}
