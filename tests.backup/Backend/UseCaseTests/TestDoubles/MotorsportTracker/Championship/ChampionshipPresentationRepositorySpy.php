<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\SaveChampionshipPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property ChampionshipPresentation[] $objects
 *
 * @method ChampionshipPresentation[]    all()
 * @method null|ChampionshipPresentation get(UuidValueObject $id)
 * @method ChampionshipPresentation      safeGet(UuidValueObject $id)
 */
final class ChampionshipPresentationRepositorySpy extends AbstractRepositorySpy implements SaveChampionshipPresentationGateway
{
    public function save(ChampionshipPresentation $championshipPresentation): void
    {
        $this->add($championshipPresentation);
    }

    public function latest(UuidValueObject $championshipId): ?ChampionshipPresentation
    {
        /** @var ?ChampionshipPresentation $latest */
        $latest = null;

        foreach ($this->objects as $championshipPresentation) {
            if (false === $championshipId->equals($championshipPresentation->championshipId())) {
                continue;
            }

            if (null === $latest) {
                $latest = $championshipPresentation;

                continue;
            }

            if ($championshipPresentation->createdOn()->value() > $latest->createdOn()->value()) {
                $latest = $championshipPresentation;
            }
        }

        return $latest;
    }
}
