<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SaveTeamRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SaveTeamRepositoryUsingDoctrine
 */
final class SaveTeamRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveATeam(): void
    {
        $team = Team::instance(new UuidValueObject(self::uuid()), new NullableUuidValueObject(self::uuid()));

        $repository = new SaveTeamRepositoryUsingDoctrine(self::connection());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }

    public function testItCanSaveATeamWithNullValues(): void
    {
        $team = Team::instance(new UuidValueObject(self::uuid()), new NullableUuidValueObject(null));

        $repository = new SaveTeamRepositoryUsingDoctrine(self::connection());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }
}
