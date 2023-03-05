<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;

interface SaveStepTypeGateway
{
    public function save(StepType $stepType): void;
}
