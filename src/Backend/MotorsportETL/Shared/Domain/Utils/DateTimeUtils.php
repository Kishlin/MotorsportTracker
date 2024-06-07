<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain\Utils;

use DateTimeImmutable;
use Kishlin\Backend\Tools\Helpers\DateTimeImmutableHelper;

final class DateTimeUtils
{
    private function __construct(
    ) {}

    public static function dateTimeOrNull(?int $timestamp): ?DateTimeImmutable
    {
        if (null === $timestamp) {
            return null;
        }

        return DateTimeImmutableHelper::fromTimestamp($timestamp);
    }
}
