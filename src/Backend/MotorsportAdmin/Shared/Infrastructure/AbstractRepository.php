<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\Gateway;
use Kishlin\Backend\Persistence\Core\QueryBuilder\OrderBy;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

abstract readonly class AbstractRepository extends ReadRepository implements Gateway
{
    public function find(string $location, array $criteria = [], array $sorts = [], ?int $limit = null): array
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('*')
            ->from($location)
        ;

        $wheres = [];
        foreach ($criteria as $key => $criterion) {
            $filters = [];

            foreach ($criterion as $column => $value) {
                if (null === $value) {
                    $filters[] = "{$column} IS NULL";

                    continue;
                }

                $paramKey = "{$column}{$key}";

                $filters[] = "{$column} = :{$paramKey}";
                $qb->withParam($paramKey, $value);
            }

            $wheres[] = '(' . implode(' AND ', $filters) . ')';
        }

        if (false === empty($wheres)) {
            $qb->andWhere(implode(' OR ', $wheres));
        }

        foreach ($sorts as $column => $order) {
            $orderBy = 'asc' === strtolower($order) ? OrderBy::ASC : OrderBy::DESC;
            $qb->addOrderBy($column, $orderBy);
        }

        if (null !== $limit) {
            $qb->limit($limit);
        }

        /** @var array<int|string, array<int|string, mixed>> $ret */
        $ret = $this->connection->execute($qb->buildQuery())->fetchAllAssociative();

        assert(is_array($ret));

        return $ret;
    }
}
