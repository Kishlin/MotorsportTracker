<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder;

enum OrderBy: String
{
    case DESC = 'DESC';

    case ASC = 'ASC';
}
