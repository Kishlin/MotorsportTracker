<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\SaveEntryRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\SaveEntryRepository
 */
final class SaveEntryRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAnEntry(): void
    {
        self::loadFixture('country.country.netherlands');
        self::loadFixture('motorsport.team.team.redBullRacing');
        self::loadFixture('motorsport.driver.driver.maxVerstappen');
        self::loadFixture('motorsport.event.eventSession.dutchGrandPrix2022Race');

        $entry = Entry::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('country.country.netherlands')),
            new UuidValueObject(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new UuidValueObject(self::fixtureId('motorsport.event.eventSession.dutchGrandPrix2022Race')),
            new UuidValueObject(self::fixtureId('motorsport.team.team.redBullRacing')),
            new PositiveIntValueObject(33),
        );

        $repository = new SaveEntryRepository(self::connection());

        $repository->save($entry);

        self::assertAggregateRootWasSaved($entry);
    }
}
