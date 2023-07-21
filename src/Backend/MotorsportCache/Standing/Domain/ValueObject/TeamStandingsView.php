<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject;

use JsonException;
use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

final class TeamStandingsView extends JsonValueObject
{
    /**
     * @param array<array{
     *     id: string,
     *     series_class: string,
     *     name: string,
     *     position: int,
     *     points: float,
     *     color: string,
     *     country: null|string,
     * }> $standings
     *
     * @throws JsonException
     */
    public static function with(array $standings): self
    {
        $data = [];

        foreach ($standings as $standing) {
            $class = $standing['series_class'] ?? 'null';
            unset($standing['series_class']);

            $standing['country'] = null !== $standing['country']
                ? json_decode($standing['country'], true, 512, JSON_THROW_ON_ERROR)
                : null;

            $data[$class][] = $standing;
        }

        return new self($data);
    }
}
