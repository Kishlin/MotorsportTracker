<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Calendar\Domain\Entity;

use Kishlin\Backend\MotorsportETL\Calendar\Domain\ValueObject\EventSessionDetails;
use Kishlin\Backend\Shared\Domain\Entity\DuplicateStrategy;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Domain\Entity\GuardedAgainstDoubles;

final class EventSession extends Entity implements GuardedAgainstDoubles
{
    private function __construct(
        private readonly Event $event,
        private readonly EventSessionDetails $details,
        private readonly SessionType $sessionType,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'event'   => $this->event,
            'details' => $this->details,
            'type'    => $this->sessionType,
        ];
    }

    public function uniquenessConstraints(): array
    {
        return [
            ['ref'],
        ];
    }

    public function strategyOnDuplicate(): DuplicateStrategy
    {
        return DuplicateStrategy::SKIP;
    }

    /**
     * @param array{
     *     uuid: string,
     *     name: string,
     *     shortName: string,
     *     shortCode: string,
     *     status: string,
     *     hasResults: bool,
     *     startTime: ?int,
     *     startTimeUtc: ?int,
     *     endTime: ?int,
     *     endTimeUtc: ?int,
     * } $data
     */
    public static function fromData(Event $event, array $data): self
    {
        return new self(
            $event,
            EventSessionDetails::fromData($data),
            SessionType::forLabel($data['name']),
        );
    }
}
