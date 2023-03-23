<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;

interface SaveClassificationGateway
{
    public function save(Classification $classification): void;
}
