<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\Gateway;

use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Result;

interface ResultGateway
{
    public function save(Result $result): void;
}
