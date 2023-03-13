<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SaveTeamRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
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
        self::loadFixture('country.country.austria');

        $team = Team::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('country.country.austria')),
            new StringValueObject('Red Bull Racing'),
            new NullableStringValueObject('#0600EF'),
            new NullableUuidValueObject('42ff413f-91b2-416b-b53f-cbfa77220a8a'),
        );

        $repository = new SaveTeamRepositoryUsingDoctrine(self::connection());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }

    public function testItCanSaveATeamWithNullValues(): void
    {
        self::loadFixture('country.country.austria');

        $team = Team::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('country.country.austria')),
            new StringValueObject('Red Bull Racing'),
            new NullableStringValueObject(null),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveTeamRepositoryUsingDoctrine(self::connection());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }
}
