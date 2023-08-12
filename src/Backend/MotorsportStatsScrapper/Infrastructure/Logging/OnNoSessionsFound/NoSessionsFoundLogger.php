<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnNoSessionsFound;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\NoSessionFoundEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class NoSessionsFoundLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(NoSessionFoundEvent $event): void
    {
        $this->logger->notice('Found no sessions to scrap.');
    }
}
