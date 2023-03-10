<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
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
        $slug      = 'team';
        $name      = 'Team';
        $code      = 'T';

        $entity = Team::create(
            new UuidValueObject($id),
            new UuidValueObject($countryId),
            new StringValueObject($slug),
            new StringValueObject($name),
            new StringValueObject($code),
        );

        self::assertItRecordedDomainEvents($entity, new TeamCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($countryId, $entity->country());
        self::assertValueObjectSame($slug, $entity->slug());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($code, $entity->code());
    }
}
