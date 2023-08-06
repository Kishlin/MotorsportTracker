<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SaveTeamRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamIfNotExists\SaveTeamRepository
 */
final class SaveTeamRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveATeam(): void
    {
        self::loadFixtures(
            'motorsport.team.team.redBullRacing',
            'motorsport.championship.season.formulaOne2022',
        );

        $team = Team::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new StringValueObject('Red Bull Racing'),
            new NullableStringValueObject('#0600EF'),
            new NullableUuidValueObject(self::uuid()),
        );

        $repository = new SaveTeamRepository(self::connection());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }

    public function testItCanSaveATeamWithNullValues(): void
    {
        self::loadFixtures(
            'motorsport.team.team.redBullRacing',
            'motorsport.championship.season.formulaOne2022',
        );

        $team = Team::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new StringValueObject('Red Bull Racing'),
            new NullableStringValueObject('#0600EF'),
            new NullableUuidValueObject(null),
        );

        $repository = new SaveTeamRepository(self::connection());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }
}
