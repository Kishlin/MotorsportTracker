<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\FindSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\SaveSeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeasonIfNotExists\SeasonCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Season[] $objects
 *
 * @method Season[]    all()
 * @method null|Season get(UuidValueObject $id)
 * @method Season      safeGet(UuidValueObject $id)
 */
final class SaveSeasonRepositorySpy extends AbstractRepositorySpy implements SaveSeasonGateway, FindSeasonGateway
{
    public function save(Season $season): void
    {
        if ($this->thereIsAlreadyASeasonForThisYear($season)) {
            throw new SeasonCreationFailureException();
        }

        $this->objects[$season->id()->value()] = $season;
    }

    public function find(string $championshipId, int $year): ?UuidValueObject
    {
        foreach ($this->objects as $season) {
            if ($championshipId === $season->championshipId()->value() && $year === $season->year()->value()) {
                return $season->id();
            }
        }

        return null;
    }

    private function thereIsAlreadyASeasonForThisYear(Season $season): bool
    {
        foreach ($this->objects as $savedSeason) {
            if ($savedSeason->championshipId()->equals($season->championshipId())
                && $savedSeason->year()->equals($season->year())) {
                return true;
            }
        }

        return false;
    }
}
