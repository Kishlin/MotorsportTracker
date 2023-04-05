<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Event\Infrastructure\Persistence\Repository\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncEventCache\EventDataRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CoreRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncEventCache\EventDataRepository
 */
final class EventDataRepositoryTest extends CoreRepositoryContractTestCase
{
    public function testItReturnsAnEmptyArrayIfThereIsNothing(): void
    {
        $repository = new EventDataRepository(self::connection());

        self::assertEmpty($repository->findAll()->data());
    }

    public function testItFindsAllEvents(): void
    {
        self::loadFixtures(
            'motorsport.event.event.emiliaRomagnaGrandPrix2022',
            'motorsport.event.event.australianGrandPrix2022',
            'motorsport.event.event.hungarianGrandPrix2019',
            'motorsport.event.event.americasMotoGP2022',
            'motorsport.event.event.dutchGrandPrix2022',
        );

        $repository = new EventDataRepository(self::connection());

        self::assertEqualsCanonicalizing(
            [
                [
                    'championship' => 'Formula One',
                    'year'         => 2022,
                    'event'        => 'Emilia Romagna GP',
                ],
                [
                    'championship' => 'Formula One',
                    'year'         => 2022,
                    'event'        => 'Australian GP',
                ],
                [
                    'championship' => 'Formula One',
                    'year'         => 2022,
                    'event'        => 'Dutch GP',
                ],
                [
                    'championship' => 'Formula One',
                    'year'         => 2019,
                    'event'        => 'Hungarian GP',
                ],
                [
                    'championship' => 'Moto GP',
                    'year'         => 2022,
                    'event'        => 'Americas GP',
                ],
            ],
            $repository->findAll()->data(),
        );
    }
}
