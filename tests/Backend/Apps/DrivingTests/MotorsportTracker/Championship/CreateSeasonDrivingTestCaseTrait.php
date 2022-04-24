<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\CreateSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to drive the use case should use this trait for its Driving Test.
 *
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait CreateSeasonDrivingTestCaseTrait
{
    public function mockCreateSeasonCommandHandler(MockObject $bus, int $year, string $championship, string $id): void
    {
        $bus
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(static function (CreateSeasonCommand $parameter) use ($year, $championship) {
                    return $year         === $parameter->year()->value()
                        && $championship === $parameter->championshipId()->value()
                    ;
                }),
            )
            ->willReturnCallback(
                static function (CreateSeasonCommand $command) use ($id) {
                    return new SeasonId($id);
                },
            )
        ;
    }
}
