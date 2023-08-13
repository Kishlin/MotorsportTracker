<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputePositionChangeGraph\ComputePositionChangeGraphCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ClassificationScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;

final readonly class ComputePositionChangeOnClassificationScrapped implements EventSubscriber
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(ClassificationScrappingSuccessEvent $event): void
    {
        $this->commandBus->execute(
            ComputePositionChangeGraphCommand::fromScalars($event->eventId()),
        );
    }
}
