<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists;

use Doctrine\ORM\NonUniqueResultException;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists\SessionTypeIdForLabelRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists\SessionTypeIdForLabelRepositoryUsingDoctrine
 */
final class SessionTypeIdForLabelRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    /**
     * @throws NonUniqueResultException
     */
    public function testItCanRetrieveAnId(): void
    {
        $this->loadFixture('motorsport.event.sessionType.race');

        $repository = new SessionTypeIdForLabelRepositoryUsingDoctrine($this->entityManager());

        $expected = self::fixtureId('motorsport.event.sessionType.race');
        $actual   = $repository->idForLabel(new StringValueObject('race'));

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testItReturnsNullIfLabelDoesNotExist(): void
    {
        $repository = new SessionTypeIdForLabelRepositoryUsingDoctrine($this->entityManager());

        self::assertNull($repository->idForLabel(new StringValueObject('race')));
    }
}
