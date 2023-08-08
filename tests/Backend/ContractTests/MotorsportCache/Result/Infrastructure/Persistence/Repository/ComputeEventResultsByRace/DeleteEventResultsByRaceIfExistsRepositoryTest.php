<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace;

use Kishlin\Backend\MotorsportCache\Result\Domain\Entity\EventResultsByRace;
use Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace\DeleteEventResultsBySessionsIfExistsRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Result\Infrastructure\Persistence\Repository\ComputeEventResultsByRace\DeleteEventResultsBySessionsIfExistsRepository
 */
final class DeleteEventResultsByRaceIfExistsRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItReturnsFalseWhenItDeletesNothing(): void
    {
        $repository = new DeleteEventResultsBySessionsIfExistsRepository(self::connection());

        self::assertFalse($repository->deleteIfExists('94ea322a-2a5e-4f3a-8303-73863dbf5206'));
    }

    public function testItReturnsTrueWhenItDeletesSomething(): void
    {
        self::loadFixture('motorsport.result.eventResultsByRace.firstTwoAtFormulaOneBahrainGP2023');

        $repository = new DeleteEventResultsBySessionsIfExistsRepository(self::connection());

        self::assertTrue($repository->deleteIfExists('00109fe5-1f07-489b-8e41-a5d9ab992423'));

        self::assertTableIsEmpty(EventResultsByRace::class);
    }
}
