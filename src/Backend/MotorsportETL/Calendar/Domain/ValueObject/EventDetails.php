<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\ValueObject;

use DateTimeImmutable;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Utils\DateTimeUtils;
use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class EventDetails implements Mapped
{
    private function __construct(
        private int $index,
        private string $name,
        private ?string $shortName,
        private ?string $status,
        private ?DateTimeImmutable $startDate,
        private ?DateTimeImmutable $endDate,
        private ?string $short_code,
        private string $ref,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'index'      => $this->index,
            'name'       => $this->name,
            'short_name' => $this->shortName,
            'status'     => $this->status,
            'start_date' => $this->startDate,
            'end_date'   => $this->endDate,
            'short_code' => $this->short_code,
            'ref'        => $this->ref,
        ];
    }

    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     startDate: ?int,
     *     startTimeUtc: ?int,
     *     endDate: ?int,
     *     endTimeUtc: ?int,
     * } $data
     */
    public static function fromData(int $index, array $data): self
    {
        return new self(
            $index,
            $data['name'],
            $data['shortName'],
            $data['status'],
            DateTimeUtils::dateTimeOrNull($data['startTimeUtc']),
            DateTimeUtils::dateTimeOrNull($data['endTimeUtc']),
            $data['shortCode'],
            $data['uuid'],
        );
    }
}
