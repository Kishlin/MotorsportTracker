<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists\SaveSessionTypeRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreLegacyRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateSessionTypeIfNotExists\SaveSessionTypeRepositoryUsingDoctrine
 */
final class SaveSessionTypeRepositoryUsingDoctrineTest extends CoreLegacyRepositoryContractTestCase
{
    public function testItCanSaveASessionType(): void
    {
        $sessionType = SessionType::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('race'),
        );

        $repository = new SaveSessionTypeRepositoryUsingDoctrine(self::entityManager());

        $repository->save($sessionType);

        self::assertAggregateRootWasSaved($sessionType);
    }
}