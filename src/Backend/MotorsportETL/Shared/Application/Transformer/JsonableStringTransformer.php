<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Application\Transformer;

use InvalidArgumentException;
use Kishlin\Backend\Shared\Application\Service\Parser\Json\JsonableStringParser;
use Kishlin\Backend\Shared\Application\Service\Parser\Json\ParserException;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final readonly class JsonableStringTransformer
{
    public function __construct(
        private JsonableStringParser $parser,
        private EventDispatcher $eventDispatcher,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function transform(mixed $extractorResponse): array
    {
        try {
            return $this->parser->parse($extractorResponse);
        } catch (InvalidArgumentException|ParserException $e) {
            $this->eventDispatcher->dispatch(JsonableStringParserExceptionEvent::forException($e));

            return [];
        }
    }
}
