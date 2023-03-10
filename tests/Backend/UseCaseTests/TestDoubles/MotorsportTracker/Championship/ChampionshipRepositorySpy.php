<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\SaveChampionshipGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SearchChampionshipGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
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
        if ($this->isADuplicate($championship)) {
            throw new Exception();
        }

        $this->objects[$championship->id()->value()] = $championship;
    }

    public function findIfExists(StringValueObject $shortCode, NullableUuidValueObject $ref): ?UuidValueObject
    {
        foreach ($this->objects as $championship) {
            if ($championship->shortCode()->equals($shortCode) || $championship->ref()->equals($ref)) {
                return $championship->id();
            }
        }

        return null;
    }

    private function isADuplicate(Championship $championship): bool
    {
        foreach ($this->objects as $savedChampionship) {
            if ($savedChampionship->name()->equals($championship->name())
                || $savedChampionship->ref()->equals($championship->ref())
                || $savedChampionship->shortCode()->equals($championship->shortCode())) {
                return true;
            }
        }

        return false;
    }
}
