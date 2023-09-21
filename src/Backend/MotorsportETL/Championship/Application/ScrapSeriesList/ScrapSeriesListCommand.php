<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Championship\Application\ScrapSeriesList;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class ScrapSeriesListCommand implements Command
{
    private function __construct(
    ) {
    }

    public static function create(): self
    {
        return new self();
    }
}
