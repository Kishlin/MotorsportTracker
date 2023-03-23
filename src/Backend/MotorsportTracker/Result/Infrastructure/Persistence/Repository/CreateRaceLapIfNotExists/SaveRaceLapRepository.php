<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists\SaveRaceLapGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\RaceLap;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveRaceLapRepository extends CoreRepository implements SaveRaceLapGateway
{
    public function save(RaceLap $raceLap): void
    {
        $this->persist($raceLap);
    }
}
