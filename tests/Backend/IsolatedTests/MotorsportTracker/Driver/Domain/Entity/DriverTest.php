<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Driver\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Driver\Domain\DomainEvent\DriverCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Driver\Domain\Entity\Driver
 */
final class DriverTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id      = '2012b677-11fa-4ac3-b630-56d9a421728d';
        $name    = 'name';
        $code    = 'code';
        $country = '5fde5d44-67d9-4928-88dc-412555d87f4a';
        $ref     = '22292c6f-988d-49b6-99e8-79346a95d36f';

        $entity = Driver::create(
            new UuidValueObject($id),
            new StringValueObject($name),
            new StringValueObject($code),
            new NullableUuidValueObject($country),
            new NullableUuidValueObject($ref),
        );

        self::assertItRecordedDomainEvents($entity, new DriverCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($code, $entity->shortCode());
        self::assertValueObjectSame($country, $entity->country());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
