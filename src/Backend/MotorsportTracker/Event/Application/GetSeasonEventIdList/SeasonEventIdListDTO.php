<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList;

final readonly class SeasonEventIdListDTO
{
    /**
     * @param string[] $idList
     */
    private function __construct(
        private array $idList,
    ) {}

    /**
     * @return string[]
     */
    public function idList(): array
    {
        return $this->idList;
    }

    /**
     * @param string[] $idList
     *
     * @return static
     */
    public static function forList(array $idList): self
    {
        return new self($idList);
    }
}
