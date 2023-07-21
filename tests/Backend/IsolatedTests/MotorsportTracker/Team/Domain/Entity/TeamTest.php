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
        $id        = '6bb7141a-134f-47d8-b9ab-0cc52fca980a';
        $seasonId  = '56ed83ee-b4a1-43fd-b364-751519639ecc';
        $countryId = 'bd4f36e3-d11f-4e8a-8380-6a492f6bdf07';
        $name      = 'Team';
        $color     = 'c';
        $ref       = '9e861bb2-a068-4087-b303-2e7ad0cecfe9';

        $entity = Team::create(
            new UuidValueObject($id),
            new UuidValueObject($seasonId),
            new UuidValueObject($countryId),
            new StringValueObject($name),
            new NullableStringValueObject($color),
            new NullableUuidValueObject($ref)
        );

        self::assertItRecordedDomainEvents($entity, new TeamCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($seasonId, $entity->season());
        self::assertValueObjectSame($countryId, $entity->country());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($color, $entity->color());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
