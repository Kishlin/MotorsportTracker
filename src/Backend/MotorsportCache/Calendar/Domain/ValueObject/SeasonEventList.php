<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

/**
 * @property array<string, array{
 *     id: string,
 *     name: string,
 *     slug: string,
 *     index: int,
 * }> $value
 */
final class SeasonEventList extends JsonValueObject
{
    /**
     * @return array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }>
     */
    public function data(): array
    {
        return $this->value;
    }

    /**
     * @param array<string, array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     index: int,
     * }> $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
