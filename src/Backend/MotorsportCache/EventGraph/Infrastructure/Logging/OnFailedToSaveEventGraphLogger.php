<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Infrastructure\Logging;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ApplicationEvent\FailedToSaveEventGraphEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class OnFailedToSaveEventGraphLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(FailedToSaveEventGraphEvent $event): void
    {
        $throwable = $event->throwable();

        $this->logger->warning(
            'Failed to save event graph.',
            [
                'message' => $throwable->getMessage(),
                'class'   => get_class($throwable),
                'file'    => $throwable->getFile(),
                'line'    => $throwable->getLine(),
            ]
        );
    }
}
