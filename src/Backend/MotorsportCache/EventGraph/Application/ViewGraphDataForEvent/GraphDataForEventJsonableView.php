<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application\ViewGraphDataForEvent;

use JsonException;
use Kishlin\Backend\Shared\Domain\View\JsonableView;

final class GraphDataForEventJsonableView extends JsonableView
{
    /**
     * @param array<string, array{}> $data
     */
    private function __construct(
        private readonly array $data,
    ) {}

    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param array<array{type: string, data: string}> $source
     *
     * @throws JsonException
     */
    public static function fromSource(array $source): self
    {
        $data = [];

        foreach ($source as $item) {
            /** @var array{} $decoded */
            $decoded = json_decode($item['data'], true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($decoded));

            $data[$item['type']] = $decoded;
        }

        return new self($data);
    }
}
