<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\StepTypeRepositoryUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\Event\StepTypeProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\StepTypeRepositoryUsingDoctrine
 */
final class StepTypeRepositoryUsingDoctrineTest extends RepositoryContractTestCase
{
    public function testItCanSaveAStepType(): void
    {
        $stepType = StepTypeProvider::race();

        $repository = new StepTypeRepositoryUsingDoctrine(self::entityManager());

        $repository->save($stepType);

        self::assertAggregateRootWasSaved($stepType);
    }
}
