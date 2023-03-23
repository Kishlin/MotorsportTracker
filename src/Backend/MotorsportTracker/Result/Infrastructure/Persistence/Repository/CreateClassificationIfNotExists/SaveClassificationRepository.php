<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\CreateClassificationIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\SaveClassificationGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Classification;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveClassificationRepository extends CoreRepository implements SaveClassificationGateway
{
    public function save(Classification $classification): void
    {
        $this->persist($classification);
    }
}
