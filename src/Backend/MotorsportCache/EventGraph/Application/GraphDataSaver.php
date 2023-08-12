<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;

interface GraphDataSaver
{
    public function save(string $event, Graph $graph): void;
}
