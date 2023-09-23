<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Domain;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonFilter;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SeasonIdentity;

interface SeasonGateway
{
    public function find(SeasonFilter $filter): ?SeasonIdentity;
}
