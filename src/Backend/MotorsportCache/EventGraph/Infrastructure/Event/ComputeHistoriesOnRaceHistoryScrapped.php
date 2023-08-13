<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\ComputeHistoriesForEventCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\RaceLapScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ComputeHistoriesOnRaceHistoryScrapped implements EventSubscriber
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(RaceLapScrappingSuccessEvent $event): void
    {
        $this->commandBus->execute(
            ComputeHistoriesForEventCommand::fromScalars($event->eventId()),
        );
    }
}
