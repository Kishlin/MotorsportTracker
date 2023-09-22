<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Series\Application\ScrapSeriesList;

interface SeriesCacheInvalidator
{
    public function invalidate(): void;
}
