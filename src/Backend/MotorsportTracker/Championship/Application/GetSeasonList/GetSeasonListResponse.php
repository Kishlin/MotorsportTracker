<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final readonly class GetSeasonListResponse implements Response
{
    private function __construct(
        private SeasonListDTO $yearList,
    ) {}

    public function yearListDTO(): SeasonListDTO
    {
        return $this->yearList;
    }

    public static function forDTO(SeasonListDTO $dto): self
    {
        return new self($dto);
    }
}
