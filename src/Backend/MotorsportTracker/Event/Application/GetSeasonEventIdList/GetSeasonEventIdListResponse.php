<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final readonly class GetSeasonEventIdListResponse implements Response
{
    private function __construct(
        private SeasonEventIdListDTO $eventIdList,
    ) {
    }

    public function eventIdList(): SeasonEventIdListDTO
    {
        return $this->eventIdList;
    }

    public static function forDTO(SeasonEventIdListDTO $dto): self
    {
        return new self($dto);
    }
}
