<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Infrastructure\Persistence\Repository\RegisterAdditionalDriver;

use Kishlin\Backend\MotorsportTracker\Result\Application\RegisterAdditionalDriver\SearchEntryAdditionalDriverGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SearchEntryAdditionalDriverRepository extends CoreRepository implements SearchEntryAdditionalDriverGateway
{
    public function find(UuidValueObject $entry, UuidValueObject $driver): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('e.id')
            ->from('entry_additional_driver', 'e')
            ->where($qb->expr()->eq('e.entry', ':entry'))
            ->where($qb->expr()->eq('e.driver', ':driver'))
            ->withParam('driver', $driver->value())
            ->withParam('entry', $entry->value())
            ->limit(1)
        ;

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();

        if (null === $ret) {
            return null;
        }

        assert(is_string($ret));

        return new UuidValueObject($ret);
    }
}
