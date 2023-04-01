<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnSeasonNotFound;

use Kishlin\Backend\MotorsportStatsScrapper\Application\Shared\Event\SeasonNotFoundEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class SeasonNotFoundLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SeasonNotFoundEvent $event): void
    {
        $this->logger->notice("Found no season for {$event->championship()} #{$event->year()}.");
    }
}
