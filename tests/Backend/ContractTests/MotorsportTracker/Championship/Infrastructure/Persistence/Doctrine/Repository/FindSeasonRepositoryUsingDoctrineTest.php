<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\FindSeasonRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Infrastructure\Persistence\Doctrine\Repository\FindSeasonRepositoryUsingDoctrine
 */
final class FindSeasonRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItCanFindTheSeason(): void
    {
        self::loadFixture('motorsport.championship.season.formulaOne2022');

        $repository = new FindSeasonRepositoryUsingDoctrine(self::entityManager());

        self::assertSame(
            self::fixtureId('motorsport.championship.season.formulaOne2022'),
            $repository->find(self::fixtureId('motorsport.championship.championship.formulaOne'), 2022)?->value(),
        );
    }

    /**
     * @throws Exception|NonUniqueResultException
     */
    public function testItReturnsNullWhenItFindsNothing(): void
    {
        $repository = new FindSeasonRepositoryUsingDoctrine(self::entityManager());

        self::assertNull($repository->find('41083229-042b-42d8-b5d4-9a2462d1abf5', 2022));
    }
}
