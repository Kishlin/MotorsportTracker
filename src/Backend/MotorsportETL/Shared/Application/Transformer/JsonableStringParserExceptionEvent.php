<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application\Transformer;

use InvalidArgumentException;
use Kishlin\Backend\Shared\Application\Event\ApplicationEvent;
use Kishlin\Backend\Shared\Application\Service\Parser\Json\ParserException;

final readonly class JsonableStringParserExceptionEvent implements ApplicationEvent
{
    private function __construct(
        private InvalidArgumentException|ParserException $exception,
    ) {}

    public function exception(): InvalidArgumentException|ParserException
    {
        return $this->exception;
    }

    public static function forException(InvalidArgumentException|ParserException $e): self
    {
        return new self($e);
    }
}
