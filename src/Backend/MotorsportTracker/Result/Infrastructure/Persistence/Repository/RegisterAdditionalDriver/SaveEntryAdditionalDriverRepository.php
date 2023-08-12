<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\RegisterAdditionalDriver;

use Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver\SaveEntryAdditionalDriverGateway;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\EntryAdditionalDriver;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SaveEntryAdditionalDriverRepository extends CoreRepository implements SaveEntryAdditionalDriverGateway
{
    public function saveEntryAdditionalDriver(EntryAdditionalDriver $entryAdditionalDriver): void
    {
        $this->persist($entryAdditionalDriver);
    }
}
