<?php

namespace Kishlin\Backend\MotorsportETL\Shared\Domain;

enum Context: String
{
    case SERIES = 'series';

    case SEASONS = 'seasons';
}
