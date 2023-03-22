<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;

interface SaveRetirementGateway
{
    public function save(Retirement $retirement): void;
}
