<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists\SaveEventSessionRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists\SaveEventSessionRepositoryUsingDoctrine
 */
final class SaveEventSessionRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
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
            new BoolValueObject(false),
            new NullableDateTimeValueObject(new DateTimeImmutable('2022-09-04 15:00:00')),
            new NullableDateTimeValueObject(new DateTimeImmutable('2022-09-04 16:00:00')),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveEventSessionRepositoryUsingDoctrine(self::connection());

        $repository->save($eventSession);

        self::assertAggregateRootWasSaved($eventSession);
    }
}
