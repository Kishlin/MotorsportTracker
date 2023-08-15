<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnClassificationScrappingSuccess;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ClassificationScrappingSuccessEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class ClassificationScrappingSuccessLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ClassificationScrappingSuccessEvent $event): void
    {
        $this->logger->notice("Finished scrapping classification for event {$event->eventId()}");
    }
}
