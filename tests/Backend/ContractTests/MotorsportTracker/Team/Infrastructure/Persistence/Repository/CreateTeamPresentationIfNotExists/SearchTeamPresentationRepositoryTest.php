<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists\SearchTeamPresentationRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists\SearchTeamPresentationRepository
 */
final class SearchTeamPresentationRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanFindATeamPresentation(): void
    {
        self::loadFixture('motorsport.team.teamPresentation.redBullRacing2022');

        $repository = new SearchTeamPresentationRepository(self::connection());

        self::assertSame(
            self::fixtureId('motorsport.team.teamPresentation.redBullRacing2022'),
            $repository->findByTeamAndSeason(
                new UuidValueObject($this->fixtureId('motorsport.team.team.redBullRacing')),
                new UuidValueObject($this->fixtureId('motorsport.championship.season.formulaOne2022')),
            )?->value(),
        );
    }

    public function testItReturnsNullWhenTeamPresentationIsNotFound(): void
    {
        $repository = new SearchTeamPresentationRepository(self::connection());

        self::assertNull($repository->findByTeamAndSeason(
            new UuidValueObject('ba48bfce-fea8-483b-97c8-3ac83a14f66c'),
            new UuidValueObject('4ffd1308-e147-46b4-ad13-861dc967184e'),
        ));
    }
}
