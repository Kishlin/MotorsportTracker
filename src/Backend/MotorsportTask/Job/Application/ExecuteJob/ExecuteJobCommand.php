<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Application\ExecuteJob;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final readonly class ExecuteJobCommand implements Command
{
    private function __construct(
        private string $job,
    ) {
    }

    public function job(): string
    {
        return $this->job;
    }

    public static function forJob(string $job): self
    {
        return new self($job);
    }
}
