<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Team\Domain\Entity\TeamPresentation;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists\SaveTeamPresentationRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Repository\CreateTeamPresentationIfNotExists\SaveTeamPresentationRepository
 */
final class SaveTeamPresentationRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveATeam(): void
    {
        self::loadFixtures(
            'country.country.austria',
            'motorsport.team.team.redBullRacing',
            'motorsport.championship.season.formulaOne2022',
        );

        $teamPresentation = TeamPresentation::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.team.team.redBullRacing')),
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new UuidValueObject(self::fixtureId('country.country.austria')),
            new StringValueObject('Red Bull Racing'),
            new NullableStringValueObject('#0600EF'),
        );

        $repository = new SaveTeamPresentationRepository(self::connection());

        $repository->save($teamPresentation);

        self::assertAggregateRootWasSaved($teamPresentation);
    }

    public function testItCanSaveATeamWithNullValues(): void
    {
        self::loadFixtures(
            'country.country.austria',
            'motorsport.team.team.redBullRacing',
            'motorsport.championship.season.formulaOne2022',
        );

        $teamPresentation = TeamPresentation::instance(
            new UuidValueObject(self::uuid()),
            new UuidValueObject(self::fixtureId('motorsport.team.team.redBullRacing')),
            new UuidValueObject(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new UuidValueObject(self::fixtureId('country.country.austria')),
            new StringValueObject('Red Bull Racing'),
            new NullableStringValueObject(null),
        );

        $repository = new SaveTeamPresentationRepository(self::connection());

        $repository->save($teamPresentation);

        self::assertAggregateRootWasSaved($teamPresentation);
    }
}
