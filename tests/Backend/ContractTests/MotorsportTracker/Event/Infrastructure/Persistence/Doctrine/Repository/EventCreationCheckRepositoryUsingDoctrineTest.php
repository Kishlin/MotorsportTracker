<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventIndex;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventLabel;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventSeasonId;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventCreationCheckRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\EventCreationCheckRepositoryUsingDoctrine
 */
final class EventCreationCheckRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventExistsWithTheSameLabel(): void
    {
        self::loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new EventCreationCheckRepositoryUsingDoctrine(self::entityManager());

        self::assertTrue($repository->seasonHasEventWithIndexOrVenue(
            new EventSeasonId(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new EventIndex(0),
            new EventLabel('Dutch GP'),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsTrueIfAnEventExistsWithTheSameIndex(): void
    {
        self::loadFixture('motorsport.event.event.dutchGrandPrix2022');

        $repository = new EventCreationCheckRepositoryUsingDoctrine(self::entityManager());

        self::assertTrue($repository->seasonHasEventWithIndexOrVenue(
            new EventSeasonId(self::fixtureId('motorsport.championship.season.formulaOne2022')),
            new EventIndex(14),
            new EventLabel('Free Label'),
        ));
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsFalseIfTheIndexAndLabelAreFreeToUse(): void
    {
        $repository = new EventCreationCheckRepositoryUsingDoctrine(self::entityManager());

        self::assertFalse($repository->seasonHasEventWithIndexOrVenue(
            new EventSeasonId(self::uuid()),
            new EventIndex(0),
            new EventLabel('Free Label'),
        ));
    }
}
