<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\DataFix\Infrastructure;

use Kishlin\Backend\MotorsportETL\DataFix\Application\FixMissingConstructorTeams\FixMissingConstructorTeamsGateway;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class FixMissingConstructorTeamsRepository extends WriteRepository implements FixMissingConstructorTeamsGateway, CoreRepositoryInterface
{
    private const QUERY = <<<'SQL'
INSERT INTO constructor_team (id, constructor, team)
SELECT DISTINCT 
    CONCAT(
        LPAD(TO_HEX(FLOOR(RANDOM() * 2147483647)::int), 8, '0'), '-',
        LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0'), '-',
        LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0'), '-',
        LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0'), '-',
        LPAD(TO_HEX(FLOOR(RANDOM() * 2147483647)::int), 8, '0'),
        LPAD(TO_HEX(FLOOR(RANDOM() * 65535)::int), 4, '0')
    ),
    ct.constructor,
    t.id
FROM team t
         JOIN constructor_team ct ON t.ref = (SELECT t2.ref FROM team t2 WHERE t2.id = ct.team)
         LEFT JOIN constructor_team ct_existing ON t.id = ct_existing.team
WHERE ct_existing.team IS NULL
GROUP BY ct.constructor, t.id;
SQL;

    public function fixMissingData(): void
    {
        $this->connection->execute(SQLQuery::create(self::QUERY));
    }
}
