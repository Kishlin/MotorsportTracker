<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

enum Join: String
{
    case INNER = 'INNER';

    case LEFT = 'LEFT';
}
