<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Championship\Domain\Entity;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipPresentationCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationColor;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationCreatedOn;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationIcon;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation
 */
final class ChampionshipPresentationTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id             = 'fcea00e3-eaa2-4e91-a169-8a3087b20ed6';
        $championshipId = 'fc90e4fb-c4a0-4c79-8561-a0d92a71886c';
        $icon           = 'icon.png';
        $color          = '#ffffff';
        $createdOn      = new DateTimeImmutable();

        $entity = ChampionshipPresentation::create(
            new ChampionshipPresentationId($id),
            new ChampionshipPresentationChampionshipId($championshipId),
            new ChampionshipPresentationIcon($icon),
            new ChampionshipPresentationColor($color),
            new ChampionshipPresentationCreatedOn($createdOn),
        );

        self::assertItRecordedDomainEvents(
            $entity,
            new ChampionshipPresentationCreatedDomainEvent(new ChampionshipPresentationId($id)),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($championshipId, $entity->championshipId());
        self::assertValueObjectSame($icon, $entity->icon());
        self::assertValueObjectSame($color, $entity->color());
        self::assertValueObjectSame($createdOn, $entity->createdOn());
    }
}
