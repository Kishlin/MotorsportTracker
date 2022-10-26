<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;

final class ViewAllChampionshipsResponse implements Response
{
    /**
     * @param ChampionshipPOPO[] $championships
     */
    private function __construct(
        private array $championships,
    ) {
    }

    /**
     * @return ChampionshipPOPO[]
     */
    public function championships(): array
    {
        return $this->championships;
    }

    /**
     * @param ChampionshipPOPO[] $championships
     */
    public static function fromChampionshipList(array $championships): self
    {
        return new self($championships);
    }
}
