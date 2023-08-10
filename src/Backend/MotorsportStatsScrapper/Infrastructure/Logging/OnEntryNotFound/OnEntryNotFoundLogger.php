<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnEntryNotFound;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\EntryNotFoundEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class OnEntryNotFoundLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(EntryNotFoundEvent $event): void
    {
        $this->logger->notice(
            "Skipping entry, session {$event->session()} car #{$event->carNumber()}",
        );
    }
}
