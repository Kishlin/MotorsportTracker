<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Monitoring;

use Kishlin\Backend\MotorsportETL\Shared\Application\Transformer\JsonableStringParserExceptionEvent;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventListener;
use Psr\Log\LoggerInterface;

final readonly class JsonableStringParseFailureLogger implements EventListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(JsonableStringParserExceptionEvent $event): void
    {
        $exception = $event->exception();

        $this->logger->error($exception->getMessage(), ['exception' => get_class($exception)]);
    }
}
