<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\ValueObject;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Utils\DateTimeUtils;
use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class EventSessionDetails implements Mapped
{
    private function __construct(
        private bool $hasResult,
        private ?DateTimeImmutable $startDate,
        private ?DateTimeImmutable $endDate,
        private string $ref,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'has_result' => $this->hasResult,
            'start_date' => $this->startDate,
            'end_date'   => $this->endDate,
            'ref'        => $this->ref,
        ];
    }

    /**
     * @param array{uuid: string, hasResults: bool, startTimeUtc: ?int, endTimeUtc: ?int} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['hasResults'],
            DateTimeUtils::dateTimeOrNull($data['startTimeUtc']),
            DateTimeUtils::dateTimeOrNull($data['endTimeUtc']),
            $data['uuid'],
        );
    }
}
