<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Entry\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists\SearchRaceLapRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists\SearchRaceLapRepository
 */
final class SearchRaceLapRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindARaceLapByEntry(): void
    {
        self::loadFixture('motorsport.result.raceLap.maxVerstappenAtDutchGP2022RaceLap10');

        $repository = new SearchRaceLapRepository(self::connection());

        $entryId = self::fixtureId('motorsport.result.entry.maxVerstappenForRedBullRacingAtDutchGP2022Race');

        $id = $repository->findForEntryAndLap(new UuidValueObject($entryId), new StrictlyPositiveIntValueObject(10));

        self::assertNotNull($id);
        self::assertSame(self::fixtureId('motorsport.result.raceLap.maxVerstappenAtDutchGP2022RaceLap10'), $id->value());
    }

    public function testItReturnsNullWhenThereAreNone(): void
    {
        $repository = new SearchRaceLapRepository(self::connection());

        self::assertNull(
            $repository->findForEntryAndLap(
                new UuidValueObject('4fadbc00-944c-4612-bd87-c8592455d787'),
                new StrictlyPositiveIntValueObject(10),
            ),
        );
    }
}
