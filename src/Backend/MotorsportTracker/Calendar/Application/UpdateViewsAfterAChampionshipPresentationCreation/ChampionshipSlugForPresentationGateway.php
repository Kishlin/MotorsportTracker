<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Calendar\Application\UpdateViewsAfterAChampionshipPresentationCreation;

use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

interface ChampionshipSlugForPresentationGateway
{
    public function findChampionshipSlugForPresentationId(UuidValueObject $presentationId): string;
}
