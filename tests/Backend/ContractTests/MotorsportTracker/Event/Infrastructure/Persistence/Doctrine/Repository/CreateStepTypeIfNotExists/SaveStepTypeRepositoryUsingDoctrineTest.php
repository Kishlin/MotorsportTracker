<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateStepTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateStepTypeIfNotExists\SaveStepTypeRepositoryUsingDoctrine;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateStepTypeIfNotExists\SaveStepTypeRepositoryUsingDoctrine
 */
final class SaveStepTypeRepositoryUsingDoctrineTest extends CoreRepositoryContractTestCase
{
    public function testItCanSaveAStepType(): void
    {
        $stepType = StepType::instance(
            new UuidValueObject(self::uuid()),
            new StringValueObject('race'),
        );

        $repository = new SaveStepTypeRepositoryUsingDoctrine(self::entityManager());

        $repository->save($stepType);

        self::assertAggregateRootWasSaved($stepType);
    }
}
