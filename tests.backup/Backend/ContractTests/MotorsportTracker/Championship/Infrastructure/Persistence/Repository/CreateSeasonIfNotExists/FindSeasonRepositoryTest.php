<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateSeasonIfNotExists;

use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateSeasonIfNotExists\FindSeasonRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Repository\CreateSeasonIfNotExists\FindSeasonRepository
 */
final class FindSeasonRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindTheSeason(): void
    {
        self::loadFixture('motorsport.championship.season.formulaOne2022');

        $repository = new FindSeasonRepository(self::connection());

        self::assertSame(
            self::fixtureId('motorsport.championship.season.formulaOne2022'),
            $repository->find(self::fixtureId('motorsport.championship.series.formulaOne'), 2022)?->value(),
        );
    }

    public function testItReturnsNullWhenItFindsNothing(): void
    {
        $repository = new FindSeasonRepository(self::connection());

        self::assertNull($repository->find('41083229-042b-42d8-b5d4-9a2462d1abf5', 2022));
    }
}
