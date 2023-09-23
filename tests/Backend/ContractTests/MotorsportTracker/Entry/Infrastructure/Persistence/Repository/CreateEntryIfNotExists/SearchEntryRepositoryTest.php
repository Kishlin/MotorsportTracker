<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateEntryIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\SearchEntryRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\SearchEntryRepository
 */
final class SearchEntryRepositoryTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateEntryIfNotExists\SearchEntryRepositoryTest::entrySearchProvider()
     */
    public function testItCanFindAnEntry(string $fixture, string $session, string $driver, string $team, int $carNumber): void
    {
        self::loadFixture($fixture);

        $repository = new SearchEntryRepository(self::connection());

        $id = $repository->find(
            new UuidValueObject($this->fixtureId($session)),
            new UuidValueObject($this->fixtureId($driver)),
            new UuidValueObject($this->fixtureId($team)),
            new PositiveIntValueObject($carNumber),
        );

        self::assertNotNull($id);
        self::assertSame(self::fixtureId($fixture), $id->value());
    }

    /**
     * @return array<array<int, int|string>>
     */
    public function entrySearchProvider(): array
    {
        return [
            [
                'motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race',
                'motorsport.event.eventSession.dutchGrandPrix2022Race',
                'motorsport.driver.driver.maxVerstappen',
                'motorsport.team.team.redBullRacing',
                33,
            ],
            [
                'motorsport.result.entry.lewisHamiltonForMercedesAtAustralianGP2022Race',
                'motorsport.event.eventSession.australianGrandPrix2022Race',
                'motorsport.driver.driver.lewisHamilton',
                'motorsport.team.team.mercedes',
                44,
            ],
        ];
    }

    public function testItReturnsNullWhenItDoesNotExist(): void
    {
        $repository = new SearchEntryRepository(self::connection());

        self::assertNull(
            $repository->find(
                new UuidValueObject('85bbce11-b159-48c8-9e9b-065ed88c5f54'),
                new UuidValueObject('987dc18a-4201-4594-9bab-be199ce94aea'),
                new UuidValueObject('58721aa8-6a64-4c0a-8e3f-8f689c9b192e'),
                new PositiveIntValueObject(33),
            ),
        );
    }
}
