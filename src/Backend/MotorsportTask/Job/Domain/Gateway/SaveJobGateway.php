<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Gateway;

use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;

interface SaveJobGateway
{
    public function save(Job $job): void;
}
