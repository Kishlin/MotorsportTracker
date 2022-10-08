<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;

interface StepTypeIdForLabelGateway
{
    public function idForLabel(StepTypeLabel $label): ?StepTypeId;
}
