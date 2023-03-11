<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists\SaveTeamRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeamIfNotExists\SaveTeamRepositoryUsingDoctrine
 */
final class SaveTeamRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveATeam(): void
    {
        self::loadFixture('country.country.austria');

        $team = Team::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('country.country.austria')),
            new StringValueObject('red-bull-racing'),
            new StringValueObject('Red Bull Racing'),
            new StringValueObject('RBR'),
        );

        $repository = new SaveTeamRepositoryUsingDoctrine(self::entityManager());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }
}
