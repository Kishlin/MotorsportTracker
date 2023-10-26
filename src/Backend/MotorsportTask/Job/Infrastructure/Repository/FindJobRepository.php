<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Infrastructure\Repository;

use Exception;
use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\FindJobGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\AdminRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class FindJobRepository extends ReadRepository implements FindJobGateway, AdminRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function find(string $id): ?Job
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('*')
            ->from('job')
            ->where($qb->expr()->eq('id', ':id'))
            ->withParam('id', $id)
            ->limit(1)
        ;

        /** @var null|array{id: string, type: string, status: string, params: string, started_on: string, finished_on: null|string} $data $data */
        $data = $this->connection->execute($qb->buildQuery())->fetchAssociative();

        if (empty($data)) {
            return null;
        }

        return Job::fromArray($data);
    }
}
