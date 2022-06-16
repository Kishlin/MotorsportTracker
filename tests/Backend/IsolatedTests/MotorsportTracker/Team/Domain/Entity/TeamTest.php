<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Team\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
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
        $name      = 'Team';
        $image     = 'https://cdn.example.com/image.webp';
        $countryId = 'bd4f36e3-d11f-4e8a-8380-6a492f6bdf07';

        $entity = Team::create(new TeamId($id), new TeamName($name), new TeamImage($image), new TeamCountryId($countryId));

        self::assertItRecordedDomainEvents($entity, new TeamCreatedDomainEvent(new TeamId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($image, $entity->image());
        self::assertValueObjectSame($countryId, $entity->countryId());
    }
}
