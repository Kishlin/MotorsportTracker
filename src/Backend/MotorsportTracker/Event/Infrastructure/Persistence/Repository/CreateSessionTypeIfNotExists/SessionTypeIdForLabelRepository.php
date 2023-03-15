<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Event\Infrastructure\Persistence\Repository\CreateSessionTypeIfNotExists;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\SessionTypeIdForLabelGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SessionTypeIdForLabelRepository extends CoreRepository implements SessionTypeIdForLabelGateway
{
    public function idForLabel(StringValueObject $label): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('st.id')
            ->from('session_type', 'st')
            ->where('st.label = :label')
            ->withParam('label', $label->value())
            ->buildQuery()
        ;

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($query)->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
