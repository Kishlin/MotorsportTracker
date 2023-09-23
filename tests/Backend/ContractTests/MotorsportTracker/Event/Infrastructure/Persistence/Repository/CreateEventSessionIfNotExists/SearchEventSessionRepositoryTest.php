<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventSessionIfNotExists;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateOrUpdateEventSession\SearchEventSessionRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateOrUpdateEventSession\SearchEventSessionRepository
 */
final class SearchEventSessionRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItReturnsTheIdWhenItExists(): void
    {
        self::loadFixture('motorsport.event.eventSession.dutchGrandPrix2022Race');

        $repository = new SearchEventSessionRepository(self::connection());

        $eventId   = $this->fixtureId('motorsport.event.event.dutchGrandPrix2022');
        $startDate = new DateTimeImmutable('022-09-04 13:00:00');

        self::assertSame(
            self::fixtureId('motorsport.event.eventSession.dutchGrandPrix2022Race'),
            $repository->search(
                new UuidValueObject($eventId),
                new UuidValueObject(self::fixtureId('motorsport.event.sessionType.race')),
                new NullableDateTimeValueObject($startDate),
            )?->id()->value(),
        );
    }

    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new SearchEventSessionRepository(self::connection());

        $eventId = '67d2b99c-c10e-4a8b-a745-617622b632e4';
        $typeId  = 'da4e3e0c-9884-4e14-9a74-d2b7dd80ffc7';

        self::assertNull($repository->search(
            new UuidValueObject($eventId),
            new UuidValueObject($typeId),
            new NullableDateTimeValueObject(null),
        ));
    }
}
