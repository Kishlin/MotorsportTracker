<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\SearchTeamViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\SearchTeamViewerUsingDoctrine
 */
final class SearchTeamViewerUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @dataProvider \Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Team\Infrastructure\Persistence\Doctrine\Repository\SearchTeamViewerUsingDoctrineTest::testItCanFindATeamProvider
     *
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItCanFindATeam(string $team): void
    {
        $this->loadFixture('motorsport.team.team.redBullRacing');

        $repository = new SearchTeamViewerUsingDoctrine(self::entityManager());

        self::assertSame(
            $this->fixtureId('motorsport.team.team.redBullRacing'),
            $repository->search($team)?->value(),
        );
    }

    /**
     * @return array<string , array{team: string}>
     */
    public function testItCanFindATeamProvider(): array
    {
        return [
            'by full name'      => ['team' => 'Red'],
            'string formatting' => ['team' => 'eDbuLlRaCIn'],
            'by partial name'   => ['team' => 'Red Bull Racing'],
        ];
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItFailsWhenThereIsMoreThanOneResult(): void
    {
        $this->loadFixtures(
            'motorsport.team.team.redBullRacing',
            'motorsport.team.team.mercedes',
        );

        $repository = new SearchTeamViewerUsingDoctrine(self::entityManager());

        self::expectException(NonUniqueResultException::class);

        $repository->search('e');
    }

    /**
     * @throws Exception
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullWhenThereIsNoResult(): void
    {
        $this->loadFixture('motorsport.team.team.redBullRacing');

        $repository = new SearchTeamViewerUsingDoctrine(self::entityManager());

        self::assertNull($repository->search('Mercedes')); // not exist
        self::assertNull($repository->search('Red Bullll Racing')); // typo
    }
}
