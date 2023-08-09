<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Infrastructure\Event;

use Kishlin\Backend\MotorsportCache\Analytics\Application\UpdateConstructorAnalyticsCache\UpdateConstructorAnalyticsCacheCommand;
use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapStandings\StandingsScrappedEvent;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class UpdateCacheOnConstructorStandingsScrapped implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(StandingsScrappedEvent $event): void
    {
        $this->logger->notice(
            "Updating constructor analytics cache after scrapping of standings for {$event->championshipName()} {$event->year()}.",
        );

        $this->commandBus->execute(
            UpdateConstructorAnalyticsCacheCommand::fromScalars($event->championshipName(), $event->year()),
        );
    }
}
