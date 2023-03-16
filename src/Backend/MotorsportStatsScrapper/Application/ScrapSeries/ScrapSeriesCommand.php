<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeries;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class ScrapSeriesCommand implements Command
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}
