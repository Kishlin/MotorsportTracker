<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\SaveChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SearchChampionshipGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Series[] $objects
 *
 * @method Series[]    all()
 * @method null|Series get(UuidValueObject $id)
 * @method Series      safeGet(UuidValueObject $id)
 */
final class ChampionshipRepositorySpy extends AbstractRepositorySpy implements SaveChampionshipGateway, SearchChampionshipGateway
{
    /**
     * @throws Exception
     */
    public function save(Series $championship): void
    {
        if ($this->isADuplicate($championship)) {
            throw new Exception();
        }

        $this->objects[$championship->id()->value()] = $championship;
    }

    public function findIfExists(StringValueObject $championship, NullableUuidValueObject $ref): ?UuidValueObject
    {
        foreach ($this->objects as $savedChampionship) {
            if ($savedChampionship->name()->equals($championship) || $savedChampionship->ref()->equals($ref)) {
                return $savedChampionship->id();
            }
        }

        return null;
    }

    private function isADuplicate(Series $championship): bool
    {
        foreach ($this->objects as $savedChampionship) {
            if ($savedChampionship->name()->equals($championship->name())
                || $savedChampionship->shortCode()->equals($championship->shortCode())
                || (null !== $savedChampionship->ref()->value() && $savedChampionship->ref()->equals($championship->ref()))) {
                return true;
            }
        }

        return false;
    }
}
