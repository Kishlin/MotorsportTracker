<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapCachableResourceCommand;

final class ScrapSeriesListCommand extends ScrapCachableResourceCommand
{
    private function __construct(
        bool $cacheMustBeInvalidated,
    ) {
        parent::__construct($cacheMustBeInvalidated);
    }

    public static function create(bool $cacheMustBeInvalidated = false): self
    {
        return new self($cacheMustBeInvalidated);
    }
}
