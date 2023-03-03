<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository;

use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Gateway\StepTypeGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class StepTypeRepositoryUsingDoctrine extends CoreRepository implements StepTypeGateway
{
    public function save(StepType $stepType): void
    {
        parent::persist($stepType);
    }
}
