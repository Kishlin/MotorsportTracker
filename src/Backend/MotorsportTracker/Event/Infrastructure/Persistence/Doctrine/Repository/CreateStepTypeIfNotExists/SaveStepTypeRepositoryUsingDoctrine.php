<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Doctrine\Repository\CreateStepTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\SaveStepTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\CoreRepository;

final class SaveStepTypeRepositoryUsingDoctrine extends CoreRepository implements SaveStepTypeGateway
{
    public function save(StepType $stepType): void
    {
        parent::persist($stepType);
    }
}
