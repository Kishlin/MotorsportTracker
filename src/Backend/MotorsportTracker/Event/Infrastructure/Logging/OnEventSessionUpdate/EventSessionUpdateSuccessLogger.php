<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Logging\OnEventSessionUpdate;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateOrUpdateEventSession\EventSessionUpdateSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class EventSessionUpdateSuccessLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(EventSessionUpdateSuccessEvent $event): void
    {
        $this->logger->info(
            'Successfully updated event session.',
            [
                'id'        => $event->existingId()->value(),
                'hasResult' => $event->hasResult()->value() ? 'Yes' : 'No',
                'endDate'   => $event->endDate()->value()?->format('Y-m-d H:i:s'),
            ]
        );
    }
}
