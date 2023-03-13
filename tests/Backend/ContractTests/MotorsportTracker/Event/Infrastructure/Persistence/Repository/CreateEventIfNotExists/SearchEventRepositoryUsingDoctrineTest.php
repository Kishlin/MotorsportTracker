<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventIfNotExists\SearchEventRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateEventIfNotExists\SearchEventRepositoryUsingDoctrine
 */
final class SearchEventRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItReturnsTheIdWhenTheSeasonAndNameAreTaken(): void
    {
        self::loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new SearchEventRepositoryUsingDoctrine(self::connection());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        $eventId = $repository->find(new UuidValueObject($seasonId), new StringValueObject('Dutch GP'), new PositiveIntValueObject(0));

        self::assertSame(self::fixtureId('motorsport.event.event.dutchGrandPrix2022'), $eventId?->value());
    }

    public function testItReturnsTheIdWhenTheSeasonAndIndexAreTaken(): void
    {
        self::loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new SearchEventRepositoryUsingDoctrine(self::connection());

        $seasonId = $this->fixtureId('motorsport.championship.season.formulaOne2022');

        $eventId = $repository->find(new UuidValueObject($seasonId), new StringValueObject(''), new PositiveIntValueObject(14));

        self::assertSame(self::fixtureId('motorsport.event.event.dutchGrandPrix2022'), $eventId?->value());
    }

    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new SearchEventRepositoryUsingDoctrine(self::connection());

        $seasonId = 'b5b06c94-8152-4a8b-8750-fdd624e52564';

        $eventId = $repository->find(new UuidValueObject($seasonId), new StringValueObject('Dutch GP'), new PositiveIntValueObject(14));
        self::assertNull($eventId);
    }
}
