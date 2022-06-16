<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;

interface StepTypeGateway
{
    public function save(StepType $stepType): void;
}
