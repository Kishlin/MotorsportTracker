<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Calendar;

use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\ChampionshipSlugForPresentationGateway;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApply;
use Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation\PresentationToApplyGateway;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\ChampionshipPresentation;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipPresentationRepositorySpy;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Championship\ChampionshipRepositorySpy;

final class CalendarEventStepDataRepositorySpy implements PresentationToApplyGateway, ChampionshipSlugForPresentationGateway
{
    /**
     * @var array<string, ChampionshipPresentation>
     */
    private array $memoizedPresentations = [];

    public function __construct(
        private readonly ChampionshipPresentationRepositorySpy $championshipPresentationRepositorySpy,
        private readonly ChampionshipRepositorySpy $championshipRepositorySpy,
    ) {
    }

    public function findChampionshipSlugForPresentationId(UuidValueObject $presentationId): string
    {
        return $this
            ->championshipRepositorySpy
            ->safeGet($this->presentationForId($presentationId)->championshipId())
            ->slug()
            ->value()
        ;
    }

    public function findData(UuidValueObject $presentationId): PresentationToApply
    {
        $presentation = $this->presentationForId($presentationId);

        return PresentationToApply::fromScalars($presentation->color()->value(), $presentation->icon()->value());
    }

    private function presentationForId(UuidValueObject $uuidValueObject): ChampionshipPresentation
    {
        $presentationId = $uuidValueObject->value();

        if (false === array_key_exists($presentationId, $this->memoizedPresentations)) {
            $this->memoizedPresentations[$presentationId] = $this->championshipPresentationRepositorySpy->safeGet(
                $uuidValueObject,
            );
        }

        return $this->memoizedPresentations[$presentationId];
    }
}
