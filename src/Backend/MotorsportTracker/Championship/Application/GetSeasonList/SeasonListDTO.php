<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\GetSeasonList;

final readonly class SeasonListDTO
{
    /**
     * @param int[] $yearList
     */
    private function __construct(
        private array $yearList,
    ) {
    }

    /**
     * @return int[]
     */
    public function yearList(): array
    {
        return $this->yearList;
    }

    /**
     * @param int[] $yearList
     *
     * @return static
     */
    public static function forList(array $yearList): self
    {
        return new self($yearList);
    }
}
