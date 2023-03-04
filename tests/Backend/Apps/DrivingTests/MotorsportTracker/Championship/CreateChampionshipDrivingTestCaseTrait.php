<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;

/**
 * Any client willing to drive the use case should use this trait for its Driving Test.
 *
 * @method callable            callback(callable $callback)
 * @method InvokedCountMatcher once()
 */
trait CreateChampionshipDrivingTestCaseTrait
{
    public function mockCreateChampionshipCommandHandler(MockObject $bus, string $name, string $slug, string $id): void
    {
        $bus
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->callback(static function (CreateChampionshipIfNotExistsCommand $parameter) use ($name, $slug) {
                    return $slug === $parameter->slug()->value() && $name === $parameter->name()->value();
                }),
            )
            ->willReturnCallback(
                static function (CreateChampionshipIfNotExistsCommand $command) use ($id) {
                    return new UuidValueObject($id);
                },
            )
        ;
    }
}
