<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SearchTeamRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SearchTeamRepositoryUsingDoctrine
 */
final class SearchTeamRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindATeam(): void
    {
        self::loadFixture('motorsport.team.team.redBullRacing');

        $repository = new SearchTeamRepositoryUsingDoctrine(self::connection());

        self::assertSame(
            self::fixtureId('motorsport.team.team.redBullRacing'),
            $repository->findByNameOrRef(new StringValueObject('Red Bull Racing'), new NullableUuidValueObject(null))?->value(),
        );
    }

    public function testItReturnsNullWhenTeamIsNotFound(): void
    {
        $repository = new SearchTeamRepositoryUsingDoctrine(self::connection());

        self::assertNull($repository->findByNameOrRef(new StringValueObject('Red Bull Racing'), new NullableUuidValueObject(null)));
    }
}
