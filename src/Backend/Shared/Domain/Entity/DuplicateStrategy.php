<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Entity;

enum DuplicateStrategy
{
    case SKIP;

    case UPDATE;
}
