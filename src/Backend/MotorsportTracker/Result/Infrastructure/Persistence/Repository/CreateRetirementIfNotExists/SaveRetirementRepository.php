<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateRetirementIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists\SaveRetirementGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveRetirementRepository extends CoreRepository implements SaveRetirementGateway
{
    public function save(Retirement $retirement): void
    {
        $this->persist($retirement);
    }
}
