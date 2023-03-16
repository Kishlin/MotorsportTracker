<?php

declare(strict_types=1);

namespace Kishlin\Backend\Country\Infrastructure\Persistence\Repository;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\SearchCountryGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;
use RuntimeException;

final class SearchCountryRepository extends CoreRepository implements SearchCountryGateway
{
    public function searchForCode(StringValueObject $code): ?UuidValueObject
    {
        $qb = $this->connection->createQueryBuilder();

        $query = $qb
            ->select('c.id')
            ->from('country', 'c')
            ->where($qb->expr()->eq('c.code', ':code'))
            ->where('c.code = :code')
            ->withParam('code', $code->value())
            ->buildQuery()
        ;

        $result = $this->connection->execute($query);

        if ($result->isFail()) {
            throw new RuntimeException();
        }

        $assoc = $result->fetchAssociative();

        return empty($assoc) ? null : new UuidValueObject((string) $assoc['id']);
    }
}
