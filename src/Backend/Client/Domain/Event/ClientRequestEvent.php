<?php

declare(strict_types=1);

namespace Kishlin\Backend\Client\Domain\Event;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;

final class ClientRequestEvent implements Event
{
    private ?string $response = null;

    /**
     * @param string[] $key
     */
    private function __construct(
        private readonly string $topic,
        private readonly array $key,
    ) {}

    public function setResponse(?string $response): void
    {
        $this->response = $response;
    }

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

    public function response(): ?string
    {
        return $this->response;
    }

    /**
     * @param string[] $key
     */
    public static function for(string $topic, array $key): self
    {
        return new self($topic, $key);
    }
}
