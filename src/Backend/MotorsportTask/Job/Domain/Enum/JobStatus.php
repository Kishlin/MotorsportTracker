<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Enum;

enum JobStatus: string
{
    case REQUESTED = 'requested';

    case RUNNING = 'running';

    case FINISHED = 'finished';
}
