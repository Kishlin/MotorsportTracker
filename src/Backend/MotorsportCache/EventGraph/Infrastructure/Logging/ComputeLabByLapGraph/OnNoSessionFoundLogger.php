<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging\ComputeLabByLapGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\NoSessionFoundEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class OnNoSessionFoundLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(NoSessionFoundEvent $event): void
    {
        $this->logger->notice('Found no session for this event.');
    }
}
