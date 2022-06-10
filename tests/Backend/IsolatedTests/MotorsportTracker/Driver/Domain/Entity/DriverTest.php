<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Driver\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverCountryId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverFirstname;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverName;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver
 */
final class DriverTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '2012b677-11fa-4ac3-b630-56d9a421728d';
        $firstname = 'firstname';
        $name      = 'name';
        $countryId = 'abdd69cd-f8dd-4028-a920-e0c5320190ab';

        $entity = Driver::create(
            new DriverId($id),
            new DriverFirstname($firstname),
            new DriverName($name),
            new DriverCountryId($countryId),
        );

        self::assertItRecordedDomainEvents($entity, new DriverCreatedDomainEvent(new DriverId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($firstname, $entity->firstname());
        self::assertValueObjectSame($countryId, $entity->countryId());
    }
}
