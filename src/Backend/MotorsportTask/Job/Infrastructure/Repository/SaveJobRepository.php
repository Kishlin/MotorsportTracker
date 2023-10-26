<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTask\Job\Infrastructure\Repository;

use JsonException;
use Kishlin\Backend\MotorsportTask\Job\Domain\Entity\Job;
use Kishlin\Backend\MotorsportTask\Job\Domain\Gateway\SaveJobGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\AdminRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class SaveJobRepository extends WriteRepository implements AdminRepositoryInterface, SaveJobGateway
{
    /**
     * @throws JsonException
     */
    public function save(Job $job): void
    {
        $qb = $this->connection->createQueryBuilder();

        /** @var array<string, null|bool|float|int|string> $data */
        $data = $job->mappedData();

        $qb
            ->select('id')
            ->from('job')
            ->where($qb->expr()->eq('id', ':id'))
            ->withParam('id', $data['id'])
            ->limit(1)
        ;

        /** @var null|string $existing */
        $existing = $this->connection->execute($qb->buildQuery())->fetchOne();

        if (empty($existing)) {
            $this->persist('job', $data);

            return;
        }

        $this->connection->update('job', $existing, $data);
    }
}
