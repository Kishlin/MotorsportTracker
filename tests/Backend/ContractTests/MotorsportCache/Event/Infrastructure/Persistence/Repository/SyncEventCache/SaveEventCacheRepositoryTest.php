<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Event\Infrastructure\Persistence\Repository\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\EventCachedAlreadyExistException;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;
use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncEventCache\SaveEventCacheRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncEventCache\SaveEventCacheRepository
 */
final class SaveEventCacheRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItCanSaveEventCached(): void
    {
        $event = EventCached::instance(
            new UuidValueObject('149884ae-4612-4608-bfc6-6be853aec413'),
            new StringValueObject('formula-one'),
            new StrictlyPositiveIntValueObject(2022),
            new StringValueObject('bahrain-grand-prix'),
        );

        $repository = new SaveEventCacheRepository(self::connection());

        $repository->save($event);

        self::assertAggregateRootWasSaved($event);
    }

    public function testItWillNotDuplicateEvents(): void
    {
        self::loadFixture('motorsport.event.events.bahrainGrandPrix');

        $event = EventCached::instance(
            new UuidValueObject('d081a9d6-bba6-4cf3-b1ea-cd764fe1413b'),
            new StringValueObject('formula-one'),
            new StrictlyPositiveIntValueObject(2022),
            new StringValueObject('bahrain-grand-prix'),
        );

        $repository = new SaveEventCacheRepository(self::connection());

        self::expectException(EventCachedAlreadyExistException::class);
        $repository->save($event);
    }
}
