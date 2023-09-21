<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\Repository;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Entity\Entity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\LocationComputer;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class UniquenessCheckRepository extends ReadRepository implements CoreRepositoryInterface
{
    public function __construct(
        private LocationComputer $locationComputer,
        Connection $connection,
    ) {
        parent::__construct($connection);
    }

    public function alreadyExists(Entity $entity): bool
    {
        $location = $this->locationComputer->computeLocation($entity);

        $qb = $this->createQueryBuilder();

        $qb
            ->select('1')
            ->from($location)
            ->limit(1)
        ;

        foreach ($entity->mappedUniqueness() as $column => $value) {
            $qb
                ->andWhere($qb->expr()->eq($column, ":{$column}"))
                ->withParam($column, $value)
            ;
        }

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();

        return 1 === $ret;
    }
}
