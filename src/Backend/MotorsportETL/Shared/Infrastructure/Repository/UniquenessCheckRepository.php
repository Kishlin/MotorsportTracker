<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\UniquenessCheckGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class UniquenessCheckRepository extends ReadRepository implements CoreRepositoryInterface, UniquenessCheckGateway
{
    /**
     * @param array<string[]>                           $uniquenessConstraints
     * @param array<string, null|bool|float|int|string> $data
     */
    public function findIfExists(array $uniquenessConstraints, string $location, array $data): ?string
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('id')
            ->from($location)
            ->limit(1)
        ;

        $wheres = [];
        foreach ($uniquenessConstraints as $constraint) {
            $filters = [];

            foreach ($constraint as $column) {
                if (null === $data[$column]) {
                    $filters[] = "{$column} IS NULL";

                    continue;
                }

                $filters[] = "{$column} = :{$column}";
                $qb->withParam($column, $data[$column]);
            }

            $wheres[] = '(' . implode(' AND ', $filters) . ')';
        }

        $qb->andWhere(implode(' OR ', $wheres));

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();

        if (empty($ret)) {
            return null;
        }

        return (string) $ret;
    }
}
