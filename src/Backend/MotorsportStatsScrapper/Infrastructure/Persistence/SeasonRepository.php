<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Persistence;

use Kishlin\Backend\MotorsportStatsScrapper\Domain\DTO\SeasonDTO;
use Kishlin\Backend\MotorsportStatsScrapper\Domain\Gateway\SeasonGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepository;

final class SeasonRepository extends CoreRepository implements SeasonGateway
{
    public function find(string $championshipName, int $year): ?SeasonDTO
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('s.id, s.ref')
            ->from('season', 's')
            ->innerJoin('championship', 'c', $qb->expr()->eq('c.id', 's.championship'))
            ->where($qb->expr()->eq('c.name', ':name'))
            ->withParam('name', $championshipName)
            ->andWhere($qb->expr()->eq('s.year', ':year'))
            ->withParam('year', $year)
            ->limit(1)
        ;

        /** @var array{id: string, ref: string}|array{} $result */
        $result = $this->connection->execute($qb->buildQuery())->fetchAssociative();

        if (empty($result)) {
            return null;
        }

        return SeasonDTO::fromData($result);
    }
}
