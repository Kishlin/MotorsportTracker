<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain;

enum StandingType: string
{
    case CONSTRUCTORS = 'constructors';

    case DRIVERS = 'drivers';

    case TEAMS = 'teams';
}
