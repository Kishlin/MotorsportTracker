<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain;

enum Context: String
{
    case CALENDAR = 'calendar';

    case SERIES = 'series';

    case SEASONS = 'seasons';

    case STANDINGS = 'standings';

    case STANDINGS_TEAMS = 'standings_teams';

    case STANDINGS_DRIVERS = 'standings_drivers';

    case STANDINGS_CONSTRUCTORS = 'standings_constructors';
}
