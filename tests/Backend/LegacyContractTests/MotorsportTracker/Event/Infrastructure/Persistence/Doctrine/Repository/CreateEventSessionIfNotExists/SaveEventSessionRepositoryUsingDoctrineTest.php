<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists\SaveEventSessionRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateEventSessionIfNotExists\SaveEventSessionRepositoryUsingDoctrine
 */
final class SaveEventSessionRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveAnEventSession(): void
    {
        self::loadFixtures(
            'motorsport.event.event.dutchGrandPrix2022',
            'motorsport.event.sessionType.race',
        );

        $eventSession = EventSession::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.event.sessionType.race')),
            new UuidValueObject(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new StringValueObject('Dutch GP Race'),
            new BoolValueObject(false),
            new NullableDateTimeValueObject(new DateTimeImmutable('2022-09-04 15:00:00')),
            new NullableDateTimeValueObject(new DateTimeImmutable('2022-09-04 16:00:00')),
        );

        $repository = new SaveEventSessionRepositoryUsingDoctrine(self::entityManager());

        $repository->save($eventSession);

        self::assertAggregateRootWasSaved($eventSession);
    }
}
