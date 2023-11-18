<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Infrastructure;

use Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory\EntryGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class EntryRepository extends ReadRepository implements CoreRepositoryInterface, EntryGateway
{
    public function find(SessionIdentity $sessionIdentity, string $carNumber): ?string
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('id')
            ->from('entry')
            ->where('session = :session')
            ->andWhere('car_number = :car_number')
            ->withParam('session', $sessionIdentity->id())
            ->withParam('car_number', $carNumber)
            ->limit(1)
        ;

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();

        if (false === is_string($ret)) {
            return null;
        }

        return $ret;
    }
}
