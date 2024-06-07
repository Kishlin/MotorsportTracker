<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Series\Application\Scrap;

use Kishlin\Backend\Shared\Domain\Bus\Task\Task;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class ScrapSeriesTask implements Task
{
    private function __construct(
        private string $job,
    ) {}

    public function job(): UuidValueObject
    {
        return new UuidValueObject($this->job);
    }

    public static function forJob(string $job): self
    {
        return new self($job);
    }
}
