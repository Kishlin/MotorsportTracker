<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\FindEntryForSessionAndNumber;

use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberRepository
 */
final class FindEntryForSessionAndNumberRepositoryTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\FindEntryForSessionAndNumber\FindEntryForSessionAndNumberRepositoryTest::entrySearchProvider()
     */
    public function testItCanFindForSessionAndNumber(string $fixture, string $session, int $carNumber): void
    {
        self::loadFixture($fixture);

        $repository = new FindEntryForSessionAndNumberRepository(self::connection());

        $id = $repository->findForSessionAndNumber(new UuidValueObject($this->fixtureId($session)), new PositiveIntValueObject($carNumber));

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
                33,
            ],
            [
                'motorsport.result.entry.lewisHamiltonForMercedesAtAustralianGP2022Race',
                'motorsport.event.eventSession.australianGrandPrix2022Race',
                44,
            ],
        ];
    }

    public function testItCannotFindItWithOnlyTheSession(): void
    {
        self::loadFixture('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $repository = new FindEntryForSessionAndNumberRepository(self::connection());

        self::assertNull(
            $repository->findForSessionAndNumber(
                new UuidValueObject($this->fixtureId('motorsport.event.eventSession.dutchGrandPrix2022Race')),
                new PositiveIntValueObject(42),
            ),
        );
    }

    public function testItCannotFindItWithOnlyTheNumber(): void
    {
        self::loadFixture('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $repository = new FindEntryForSessionAndNumberRepository(self::connection());

        self::assertNull(
            $repository->findForSessionAndNumber(
                new UuidValueObject('22e9a680-6f8c-499a-87de-4ba0971a2a48'),
                new PositiveIntValueObject(33),
            ),
        );
    }
}
