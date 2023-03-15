<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists\SaveSessionTypeRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists\SaveSessionTypeRepository
 */
final class SaveSessionTypeRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveASessionType(): void
    {
        $sessionType = SessionType::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('race'),
        );

        $repository = new SaveSessionTypeRepository(self::connection());

        $repository->save($sessionType);

        self::assertAggregateRootWasSaved($sessionType);
    }
}
