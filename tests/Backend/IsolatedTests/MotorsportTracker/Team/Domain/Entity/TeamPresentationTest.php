<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Team\Domain\Entity;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Team\Domain\DomainEvent\TeamPresentationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationName;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationTeamId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation
 */
final class TeamPresentationTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '69156109-1371-41bf-8cdb-ae3739c6b660';
        $teamId    = '3525e3d1-0b8a-4b5f-b8bf-d569b9b727a6';
        $name      = 'Team name';
        $image     = 'https://cdn.example.com/image.webp';
        $createdOn = new DateTimeImmutable();

        $teamPresentation = TeamPresentation::create(
            new TeamPresentationId($id),
            new TeamPresentationTeamId($teamId),
            new TeamPresentationName($name),
            new TeamPresentationImage($image),
            new TeamPresentationCreatedOn($createdOn),
        );

        self::assertItRecordedDomainEvents($teamPresentation, new TeamPresentationCreatedDomainEvent(new TeamPresentationId($id)));

        self::assertValueObjectSame($id, $teamPresentation->id());
        self::assertValueObjectSame($teamId, $teamPresentation->teamId());
        self::assertValueObjectSame($name, $teamPresentation->name());
        self::assertValueObjectSame($image, $teamPresentation->image());
        self::assertValueObjectSame($createdOn, $teamPresentation->createdOn());
    }
}
