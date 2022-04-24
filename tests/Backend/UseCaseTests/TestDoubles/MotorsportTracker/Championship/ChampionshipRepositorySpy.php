<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\ChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Championship[] $objects
 *
 * @method Championship get(ChampionshipId $id)
 */
final class ChampionshipRepositorySpy extends AbstractRepositorySpy implements ChampionshipGateway
{
    /**
     * @throws Exception
     */
    public function save(Championship $championship): void
    {
        if ($this->nameOrSlugIsAlreadyTaken($championship)) {
            throw new Exception();
        }

        $this->objects[$championship->id()->value()] = $championship;
    }

    private function nameOrSlugIsAlreadyTaken(Championship $championship): bool
    {
        foreach ($this->objects as $savedChampionship) {
            if ($savedChampionship->slug()->equals($championship->slug())
                || $savedChampionship->name()->equals($championship->name())) {
                return true;
            }
        }

        return false;
    }
}
