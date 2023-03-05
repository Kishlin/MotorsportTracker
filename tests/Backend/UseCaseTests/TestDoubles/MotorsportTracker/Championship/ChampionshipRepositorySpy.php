<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\SaveChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\SearchChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Championship[] $objects
 *
 * @method Championship[]    all()
 * @method null|Championship get(UuidValueObject $id)
 * @method Championship      safeGet(UuidValueObject $id)
 */
final class ChampionshipRepositorySpy extends AbstractRepositorySpy implements SaveChampionshipGateway, SearchChampionshipGateway
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

    public function findBySlug(string $slug): ?UuidValueObject
    {
        foreach ($this->objects as $championship) {
            if ($slug === $championship->slug()->value()) {
                return $championship->id();
            }
        }

        return null;
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
