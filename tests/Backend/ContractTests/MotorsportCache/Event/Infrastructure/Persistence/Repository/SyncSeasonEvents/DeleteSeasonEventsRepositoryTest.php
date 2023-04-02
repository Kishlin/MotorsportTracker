<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Event\Infrastructure\Persistence\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncSeasonEvents\DeleteSeasonEventsRepository;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncSeasonEvents\DeleteSeasonEventsRepository
 */
final class DeleteSeasonEventsRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItReturnsTrueWhenItDeletesSomething(): void
    {
        self::loadFixture('motorsport.event.seasonEvents.firstThreeEventsOfFormulaOne2022');

        $repository = new DeleteSeasonEventsRepository(self::connection());

        self::assertTrue(
            $repository->deleteIfExists(new StringValueObject('formula-one'), new StrictlyPositiveIntValueObject(2022)),
        );

        self::assertEmpty(
            self::connection()
                ->execute(SQLQuery::create('SELECT * FROM calendar_event;'))
                ->fetchAllAssociative(),
        );
    }

    public function testItReturnsFalseWhenItDeletesNothing(): void
    {
        $repository = new DeleteSeasonEventsRepository(self::connection());

        self::assertFalse(
            $repository->deleteIfExists(new StringValueObject('formula-one'), new StrictlyPositiveIntValueObject(2022)),
        );
    }
}
