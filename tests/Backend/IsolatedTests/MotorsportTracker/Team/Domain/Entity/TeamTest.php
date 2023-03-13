<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team
 */
final class TeamTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '4f56cfcc-defa-4f9f-a65c-d4758303a706';
        $countryId = 'bd4f36e3-d11f-4e8a-8380-6a492f6bdf07';
        $name      = 'Team';
        $color     = 'c';
        $ref       = '42ff413f-91b2-416b-b53f-cbfa77220a8a';

        $entity = Team::create(
            new UuidValueObject($id),
            new UuidValueObject($countryId),
            new StringValueObject($name),
            new NullableStringValueObject($color),
            new NullableUuidValueObject($ref)
        );

        self::assertItRecordedDomainEvents($entity, new TeamCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($countryId, $entity->country());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($color, $entity->color());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
