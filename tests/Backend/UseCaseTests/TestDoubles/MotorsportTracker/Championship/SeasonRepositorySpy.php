<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateSeason\SeasonCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Season;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\SeasonId;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Season[] $objects
 *
 * @method Season[]    all()
 * @method null|Season get(SeasonId $id)
 */
final class SeasonRepositorySpy extends AbstractRepositorySpy implements SeasonGateway
{
    public function save(Season $season): void
    {
        if ($this->thereIsAlreadyASeasonForThisYear($season)) {
            throw new SeasonCreationFailureException();
        }

        $this->objects[$season->id()->value()] = $season;
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
