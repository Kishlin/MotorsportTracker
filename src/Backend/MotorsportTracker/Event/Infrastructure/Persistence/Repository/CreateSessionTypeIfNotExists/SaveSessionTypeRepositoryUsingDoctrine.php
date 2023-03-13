<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\SaveSessionTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveSessionTypeRepositoryUsingDoctrine extends CoreRepository implements SaveSessionTypeGateway
{
    public function save(SessionType $sessionType): void
    {
        parent::persist($sessionType);
    }
}
