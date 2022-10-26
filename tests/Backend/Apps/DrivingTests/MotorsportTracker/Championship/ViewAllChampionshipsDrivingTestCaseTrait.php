<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ChampionshipPOPO;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to drive the use case should use this trait for its Driving Test.
 *
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait ViewAllChampionshipsDrivingTestCaseTrait
{
    /**
     * @param ChampionshipPOPO[] $championships
     */
    public function mockViewAllChampionshipsQueryHandler(MockObject $bus, array $championships): void
    {
        $bus
            ->expects($this->once())
            ->method('ask')
            ->with(
                $this->callback(static function (mixed $query) {
                    return $query instanceof ViewAllChampionshipsQuery;
                }),
            )
            ->willReturn(
                ViewAllChampionshipsResponse::fromChampionshipList($championships)
            )
        ;
    }
}
