<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

final class CalendarViewsToUpdate
{
    /**
     * @param string[] $idList
     */
    private function __construct(
        private readonly array $idList,
    ) {
    }

    /**
     * @return string[]
     */
    public function idList(): array
    {
        return $this->idList;
    }

    /**
     * @param string[] $idList
     */
    public static function fromScalars(array $idList): self
    {
        return new self($idList);
    }
}
