<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Enum;

enum JobType: string
{
    case SCRAP_SERIES = 'scrap_series';

    case SCRAP_SEASONS = 'scrap_seasons';

    case SCRAP_CALENDAR = 'scrap_calendar';

    case SCRAP_STANDINGS = 'scrap_standings';

    case SCRAP_RACE_HISTORIES = 'scrap_race_histories';

    case SCRAP_CLASSIFICATIONS = 'scrap_classifications';
}
