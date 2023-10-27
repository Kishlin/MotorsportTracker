<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\Gateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class Repository extends ReadRepository implements CoreRepositoryInterface, Gateway
{
    public function find(string $location, array $criteria = [], ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('*')
            ->from($location)
        ;

        $wheres = [];
        foreach ($criteria as $column => $value) {
            if (null === $value) {
                $wheres[] = "{$column} IS NULL";

                continue;
            }

            $wheres[] = "{$column} = :{$column}";
            $qb->withParam($column, $value);
        }

        if (false === empty($wheres)) {
            $qb->andWhere('(' . implode(' AND ', $wheres) . ')');
        }

        if (null !== $limit) {
            $qb->limit($limit);
        }

        /** @var array<int|string, mixed> $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        assert(is_array($ret));

        return $ret;
    }
}
