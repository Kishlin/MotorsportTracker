<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ChampionshipPOPO;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\ChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Championship[] $objects
 *
 * @method Championship get(ChampionshipId $id)
 */
final class ChampionshipRepositorySpy extends AbstractRepositorySpy implements ChampionshipGateway, ViewAllChampionshipsGateway
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

    public function viewAllChampionships(): array
    {
        return array_map(
            static function (Championship $championship) {
                return ChampionshipPOPO::fromScalars($championship->id()->value(), $championship->name()->value());
            },
            $this->objects,
        );
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
