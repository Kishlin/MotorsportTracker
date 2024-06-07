<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\InvalidateFrontCacheOnJobFinished;

use Kishlin\Backend\MotorsportTask\Job\Domain\Event\JobFinishedEvent;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\FindJobGateway;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventListener;
use Psr\Log\LoggerInterface;

final readonly class JobFinishedEventListener implements EventListener
{
    public function __construct(
        private FrontCacheInvalidator $cacheInvalidator,
        private FindJobGateway $findJobGateway,
        private ?LoggerInterface $logger = null,
    ) {}

    public function __invoke(JobFinishedEvent $event): void
    {
        $job = $this->findJobGateway->find($event->uuid());
        if (null === $job) {
            $this->logger?->error('Job not found', ['uuid' => $event->uuid()]);

            return;
        }

        $this->cacheInvalidator->invalidateAfter($job);

        $this->logger?->info('Handled finish event for job', ['uuid' => $event->uuid()]);
    }
}
