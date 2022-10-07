<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Racer\Application\GetAllRacersForDateTime;

use Kishlin\Backend\MotorsportTracker\Racer\Domain\View\RacerPOPO;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class GetAllRacersForDateTimeResponse implements Response
{
    /**
     * @param RacerPOPO[] $racers
     */
    private function __construct(
        private array $racers,
    ) {
    }

    /**
     * @return RacerPOPO[]
     */
    public function racers(): array
    {
        return $this->racers;
    }

    /**
     * @param RacerPOPO[] $racers
     */
    public static function fromRacers(array $racers): self
    {
        return new self($racers);
    }
}
