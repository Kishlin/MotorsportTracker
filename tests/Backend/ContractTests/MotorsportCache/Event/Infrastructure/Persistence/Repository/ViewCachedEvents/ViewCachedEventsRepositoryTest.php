<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\MotorsportCache\Event\Infrastructure\Persistence\Repository\ViewCachedEvents;

use Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\ViewCachedEvents\ViewCachedEventsRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\CacheRepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\CachedEventsJsonableView
 * @covers \Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\ViewCachedEvents\ViewCachedEventsRepository
 */
final class ViewCachedEventsRepositoryTest extends CacheRepositoryContractTestCase
{
    public function testItReturnsAnEmptyViewWhenThereAreNoEvents(): void
    {
        $repository = new ViewCachedEventsRepository(self::connection());

        self::assertEmpty($repository->findAll()->toArray());
    }

    public function testItViewsAllEvents(): void
    {
        self::loadFixtures(
            'motorsport.event.events.saudiArabianGrandPrix',
            'motorsport.event.events.australianGrandPrix',
            'motorsport.event.events.bahrainGrandPrix',
        );

        $repository = new ViewCachedEventsRepository(self::connection());

        $view = $repository->findAll();

        self::assertEqualsCanonicalizing(
            [
                [
                    'championship' => 'formula-one',
                    'year'         => 2022,
                    'event'        => 'bahrain-grand-prix',
                ],
                [
                    'championship' => 'formula-one',
                    'year'         => 2022,
                    'event'        => 'saudi-arabian-grand-prix',
                ],
                [
                    'championship' => 'formula-one',
                    'year'         => 2022,
                    'event'        => 'australian-grand-prix',
                ],
            ],
            $view->toArray(),
        );
    }
}
