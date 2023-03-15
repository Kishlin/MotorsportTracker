<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SearchTeamRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists\SearchTeamRepositoryUsingDoctrine
 */
final class SearchTeamRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindATeam(): void
    {
        self::loadFixture('motorsport.team.team.redBullRacing');

        $repository = new SearchTeamRepository(self::connection());

        self::assertSame(
            self::fixtureId('motorsport.team.team.redBullRacing'),
            $repository->findForRef(new NullableUuidValueObject('41be2072-17ab-455f-8522-8b96bc315e47'))?->value(),
        );
    }

    public function testItReturnsNullWhenTeamIsNotFound(): void
    {
        $repository = new SearchTeamRepository(self::connection());

        self::assertNull($repository->findForRef(new NullableUuidValueObject('41be2072-17ab-455f-8522-8b96bc315e47')));
    }

    public function testItReturnsNullWhenTheRefIsNull(): void
    {
        $repository = new SearchTeamRepository(self::connection());

        self::assertNull($repository->findForRef(new NullableUuidValueObject(null)));
    }
}
