<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Domain\Entity;

use DateTimeImmutable;
use Exception;
use JsonException;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobStatus;
use Kishlin\Backend\MotorsportTask\Job\Domain\Enum\JobType;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\ValueObject\DateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class Job extends Entity
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly StringValueObject $type,
        private StringValueObject $status,
        private readonly JsonValueObject $params,
        private readonly DateTimeValueObject $startedOn,
        private NullableDateTimeValueObject $finishedOn,
    ) {
    }

    /**
     * @param array<string, mixed> $params
     */
    public static function new(UuidValueObject $uuid, DateTimeImmutable $now, JobType $type, array $params): self
    {
        return new self(
            $uuid,
            new StringValueObject($type->value),
            new StringValueObject(JobStatus::REQUESTED->value),
            new JsonValueObject($params),
            new DateTimeValueObject($now),
            new NullableDateTimeValueObject(null),
        );
    }

    /**
     * @param array{
     *     id: string,
     *     type: string,
     *     status: string,
     *     params: string,
     *     started_on: string,
     *     finished_on: string|null,
     * } $data
     *
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new UuidValueObject($data['id']),
            new StringValueObject($data['type']),
            new StringValueObject($data['status']),
            JsonValueObject::fromString($data['params']),
            new DateTimeValueObject(new DateTimeImmutable($data['started_on'])),
            new NullableDateTimeValueObject($data['finished_on'] ? new DateTimeImmutable($data['finished_on']) : null),
        );
    }

    public function start(): void
    {
        $this->status = new StringValueObject(JobStatus::RUNNING->value);
    }

    public function end(DateTimeImmutable $now): void
    {
        $this->status     = new StringValueObject(JobStatus::FINISHED->value);
        $this->finishedOn = new NullableDateTimeValueObject($now);
    }

    public function hasStatus(JobStatus $status): bool
    {
        return $this->status->value() === $status->value;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'          => $this->id->value(),
            'type'        => $this->type->value(),
            'status'      => $this->status->value(),
            'params'      => $this->params->asString(),
            'started_on'  => $this->startedOn->value()->format('Y-m-d H:i:s'),
            'finished_on' => $this->finishedOn->value()?->format('Y-m-d H:i:s'),
        ];
    }
}
