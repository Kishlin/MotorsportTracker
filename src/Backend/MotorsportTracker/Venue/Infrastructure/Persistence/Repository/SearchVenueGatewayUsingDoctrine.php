<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Venue\Infrastructure\Persistence\Repository;

use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenueIfNotExists\SearchVenueGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchVenueGatewayUsingDoctrine extends CoreRepository implements SearchVenueGateway
{
    public function search(StringValueObject $name): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('v.id')
            ->from('venue', 'v')
            ->where($qb->expr()->eq('v.name', ':name'))
            ->withParam('name', $name->value())
        ;

        /** @var array<array{id: string}> $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        if (0 === count($result)) {
            return null;
        }

        if (1 !== count($result)) {
            throw new RuntimeException('More than one result.');
        }

        return new UuidValueObject($result[0]['id']);
    }
}
