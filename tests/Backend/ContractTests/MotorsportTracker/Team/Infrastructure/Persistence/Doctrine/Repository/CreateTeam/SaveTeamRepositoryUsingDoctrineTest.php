<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeam;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\Team;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamCountryId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamImage;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamName;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeam\SaveTeamRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\CreateTeam\SaveTeamRepositoryUsingDoctrine
 */
final class SaveTeamRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveATeam(): void
    {
        self::loadFixture('country.country.austria');

        $team = Team::instance(
            new TeamId(self::uuid()),
            new TeamName('Red Bull Racing'),
            new TeamImage('https://example.com/redbullracing.webp'),
            new TeamCountryId(self::fixtureId('country.country.austria')),
        );

        $repository = new SaveTeamRepositoryUsingDoctrine(self::entityManager());

        $repository->save($team);

        self::assertAggregateRootWasSaved($team);
    }
}