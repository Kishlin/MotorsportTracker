<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Result\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\UpdateEventResultsCacheCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ClassificationScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class SyncRaceResultsAfterScrapping implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(ClassificationScrappingSuccessEvent $event): void
    {
        $this->logger->notice(
            "Sync sessions results after scrapping for {$event->eventId()}",
        );

        $this->commandBus->execute(
            UpdateEventResultsCacheCommand::fromScalars($event->eventId()),
        );
    }
}
