<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Enum;

enum JobType: string
{
    case SCRAP_SERIES = 'scrap_series';

    case SCRAP_SEASONS = 'scrap_seasons';
}
