<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\EntryList\Infrastructure\Persistence\Doctrine\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\EntryList\Domain\Entity\Entry;
use Kishlin\Backend\MotorsportTracker\EntryList\Infrastructure\Persistence\Doctrine\Repository\CreateEntryIfNotExists\SaveEntryRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\EntryList\Infrastructure\Persistence\Doctrine\Repository\CreateEntryIfNotExists\SaveEntryRepositoryUsingDoctrine
 */
final class SaveEntryRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveAEntry(): void
    {
        self::loadFixtures(
            'motorsport.event.event.dutchGrandPrix2022',
            'motorsport.driver.driver.maxVerstappen',
            'motorsport.team.team.redBullRacing',
        );

        $entry = Entry::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new UuidValueObject(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new NullableUuidValueObject(self::fixtureId('motorsport.team.team.redBullRacing')),
            new StringValueObject('chassis'),
            new StringValueObject('engine'),
            new NullableStringValueObject('name'),
            new NullableStringValueObject('slug'),
            new StringValueObject('1'),
        );

        $repository = new SaveEntryRepositoryUsingDoctrine(self::entityManager());

        $repository->save($entry);

        self::assertAggregateRootWasSaved($entry);
    }

    public function testItCanBeCreatedWithNullValues(): void
    {
        self::loadFixtures(
            'motorsport.event.event.dutchGrandPrix2022',
            'motorsport.driver.driver.maxVerstappen',
        );

        $entry = Entry::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.event.event.dutchGrandPrix2022')),
            new UuidValueObject(self::fixtureId('motorsport.driver.driver.maxVerstappen')),
            new NullableUuidValueObject(null),
            new StringValueObject('chassis'),
            new StringValueObject('engine'),
            new NullableStringValueObject(null),
            new NullableStringValueObject(null),
            new StringValueObject('1'),
        );

        $repository = new SaveEntryRepositoryUsingDoctrine(self::entityManager());

        $repository->save($entry);

        self::assertAggregateRootWasSaved($entry);
    }
}
