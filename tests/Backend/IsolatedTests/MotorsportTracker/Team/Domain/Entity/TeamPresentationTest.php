<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamPresentationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation
 */
final class TeamPresentationTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '4f56cfcc-defa-4f9f-a65c-d4758303a706';
        $teamId    = '42ff413f-91b2-416b-b53f-cbfa77220a8a';
        $seasonId  = '56ed83ee-b4a1-43fd-b364-751519639ecc';
        $countryId = 'bd4f36e3-d11f-4e8a-8380-6a492f6bdf07';
        $name      = 'Team';
        $color     = 'c';

        $entity = TeamPresentation::create(
            new UuidValueObject($id),
            new UuidValueObject($teamId),
            new UuidValueObject($seasonId),
            new UuidValueObject($countryId),
            new StringValueObject($name),
            new NullableStringValueObject($color),
        );

        self::assertItRecordedDomainEvents($entity, new TeamPresentationCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($teamId, $entity->team());
        self::assertValueObjectSame($seasonId, $entity->season());
        self::assertValueObjectSame($countryId, $entity->country());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($color, $entity->color());
    }
}
