<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Task;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use RuntimeException;

final class TaskNotRegisteredError extends RuntimeException
{
    public function __construct(Task $task)
    {
        $taskClass = $task::class;

        parent::__construct("The task <{$taskClass}> has no task handler associated");
    }
}
