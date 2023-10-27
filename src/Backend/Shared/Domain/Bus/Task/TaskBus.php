<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Task;

interface TaskBus
{
    public function execute(Task $task): bool;
}
