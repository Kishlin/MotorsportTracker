<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists\SaveEventRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventIfNotExists\SaveEventRepositoryUsingDoctrine
 */
final class SaveEventRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnEvent(): void
    {
        self::loadFixtures(
            'motorsport.venue.venue.zandvoort',
            'motorsport.championship.season.formulaOne2022',
        );

        $event = Event::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new UuidValueObject(self::fixtureId('motorsport.venue.venue.zandvoort')),
            new PositiveIntValueObject(14),
            new StringValueObject('Dutch Grand Prix'),
            new StringValueObject('dutch-gp'),
            new NullableStringValueObject('Dutch GP'),
            new NullableDateTimeValueObject(new DateTimeImmutable('2022-11-22 01:00:00')),
            new NullableDateTimeValueObject(new DateTimeImmutable('2022-11-22 02:00:00')),
        );

        $repository = new SaveEventRepositoryUsingDoctrine(self::entityManager());

        $repository->save($event);

        self::assertAggregateRootWasSaved($event);
    }

    public function testItCanSaveAnEventWithNullValues(): void
    {
        self::loadFixtures(
            'motorsport.venue.venue.zandvoort',
            'motorsport.championship.season.formulaOne2022',
        );

        $id = self::uuid();

        $event = Event::instance(
            new UuidValueObject($id),
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new UuidValueObject(self::fixtureId('motorsport.venue.venue.zandvoort')),
            new PositiveIntValueObject(14),
            new StringValueObject('Dutch Grand Prix'),
            new StringValueObject('dutch-gp'),
            new NullableStringValueObject(null),
            new NullableDateTimeValueObject(null),
            new NullableDateTimeValueObject(null),
        );

        $repository = new SaveEventRepositoryUsingDoctrine(self::entityManager());

        $repository->save($event);

        self::assertAggregateRootWasSaved($event);

        $saved = self::entityManager()->getRepository(Event::class)->find($id);

        self::assertInstanceOf(Event::class, $saved);

        self::assertNull($saved->shortName()->value());
        self::assertNull($saved->startDate()->value());
        self::assertNull($saved->endDate()->value());
    }
}
