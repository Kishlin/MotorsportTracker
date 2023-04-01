<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncSeasonEvents;

use Kishlin\Backend\MotorsportCache\Calendar\Domain\Entity\SeasonEvents;
use Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject\SeasonEventList;
use Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncSeasonEvents\SaveSeasonEventsRepository;
use Kishlin\Backend\Shared\Domain\ValueObject\StrictlyPositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Calendar\Infrastructure\Persistence\Repository\SyncSeasonEvents\SaveSeasonEventsRepository
 */
final class SaveSeasonEventsRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItCanSaveSeasonEvents(): void
    {
        $seasonEvents = SeasonEvents::instance(
            new UuidValueObject('97436f93-d78b-4115-9e94-be08bbee84dd'),
            new StringValueObject('Formula One'),
            new StrictlyPositiveIntValueObject(2022),
            SeasonEventList::fromData($this->events()),
        );

        $repository = new SaveSeasonEventsRepository(self::connection());

        $repository->save($seasonEvents);

        self::assertAggregateRootWasSaved($seasonEvents);
    }

    /**
     * @return array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }>
     */
    private function events(): array
    {
        return [
            'bahrain-grand-prix' => [
                'id'    => '85f72012-ab0f-4794-bd95-a3b35bfde3b7',
                'name'  => 'Bahrain Grand Prix',
                'slug'  => 'bahrain-grand-prix',
                'index' => 0,
            ],
            'saudi-arabian-grand-prix' => [
                'id'    => '57381c8f-bb99-4315-9483-32f1d325cdd7',
                'name'  => 'Saudi Arabian Grand Prix',
                'slug'  => 'saudi-arabian-grand-prix',
                'index' => 1,
            ],
            'australian-grand-prix' => [
                'id'    => 'aa8d6db8-f460-43e5-86ee-167d8681cae6',
                'name'  => 'Australian Grand Prix',
                'slug'  => 'australian-grand-prix',
                'index' => 2,
            ],
        ];
    }
}
