<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnClassificationScrappingFailure;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ClassificationScrappingFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final class ClassificationScrappingFailureLogger implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(ClassificationScrappingFailureEvent $event): void
    {
        $classificationData = $event->classification();

        $this->logger->error("Failed to scrap classification for car {$classificationData['carNumber']}.", $classificationData);
    }
}
