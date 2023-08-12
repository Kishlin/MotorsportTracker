<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Logging\OnRetirementScrappingFailure;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\RetirementScrappingFailureEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Psr\Log\LoggerInterface;

final readonly class RetirementScrappingFailureLogger implements EventSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(RetirementScrappingFailureEvent $event): void
    {
        $retirementData = $event->retirement();

        $this->logger->error("Failed to scrap retirement for car {$retirementData['carNumber']}.", $retirementData);
        $this->logger->error($event->throwable()->getMessage());
    }
}
