<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\Result;

enum ResultFailure
{
    case CONNECTION_ERROR;

    case QUERY_ERROR;
}
