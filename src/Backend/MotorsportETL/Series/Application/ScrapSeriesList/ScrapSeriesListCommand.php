<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapWithCacheCommand;

final class ScrapSeriesListCommand extends ScrapWithCacheCommand
{
    private function __construct(
    ) {
    }

    public static function create(): self
    {
        return new self();
    }
}
