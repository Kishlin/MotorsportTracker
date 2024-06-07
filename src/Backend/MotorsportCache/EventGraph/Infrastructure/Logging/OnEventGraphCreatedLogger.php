<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\DomainEvent\EventGraphCreatedDomainEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class OnEventGraphCreatedLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(EventGraphCreatedDomainEvent $event): void
    {
        $this->logger->notice("Successfully created event graph # {$event->aggregateUuid()->value()}");
    }
}
