<?php

declare(strict_types=1);

namespace Kishlin\Backend\Client\Domain\Event;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final readonly class ClientResponseEvent implements Event
{
    /**
     * @param string[] $key
     */
    private function __construct(
        private string $topic,
        private array $key,
        private string $response,
    ) {}

    public function topic(): string
    {
        return $this->topic;
    }

    /**
     * @return string[]
     */
    public function key(): array
    {
        return $this->key;
    }

    public function response(): string
    {
        return $this->response;
    }

    /**
     * @param string[] $key
     */
    public static function for(string $topic, array $key, string $response): self
    {
        return new self($topic, $key, $response);
    }
}
