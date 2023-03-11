<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\QueryBuilder\Expression;

enum ComparisonComparator: String
{
    case EQ = '=';

    case NEQ = '<>';

    case LT = '<';

    case LTE = '<=';

    case GT = '>';

    case GTE = '>=';
}
