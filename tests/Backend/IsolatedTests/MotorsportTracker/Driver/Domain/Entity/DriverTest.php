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
        $id        = '2012b677-11fa-4ac3-b630-56d9a421728d';
        $name      = 'name';
        $code      = 'code';
        $countryId = 'abdd69cd-f8dd-4028-a920-e0c5320190ab';
        $ref       = '22292c6f-988d-49b6-99e8-79346a95d36f';

        $entity = Driver::create(
            new UuidValueObject($id),
            new StringValueObject($name),
            new StringValueObject($code),
            new UuidValueObject($countryId),
            new NullableUuidValueObject($ref),
        );

        self::assertItRecordedDomainEvents($entity, new DriverCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($code, $entity->shortCode());
        self::assertValueObjectSame($countryId, $entity->countryId());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
