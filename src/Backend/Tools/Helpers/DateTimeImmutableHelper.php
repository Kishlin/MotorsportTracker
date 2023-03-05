<?php

declare(strict_types=1);

namespace Kishlin\Backend\Tools\Helpers;

use DateTimeImmutable;

final class DateTimeImmutableHelper
{
    public static function fromTimestamp(int $timestamp): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($timestamp);
    }

    public static function fromNullableTimestamp(?int $timestamp): ?DateTimeImmutable
    {
        if (null === $timestamp) {
            return null;
        }

        return (new DateTimeImmutable())->setTimestamp($timestamp);
    }
}
