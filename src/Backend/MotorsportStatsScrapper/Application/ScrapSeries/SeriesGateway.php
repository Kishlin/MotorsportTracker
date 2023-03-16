<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapSeries;

interface SeriesGateway
{
    public function fetch(): SeriesGetawayResponse;
}
