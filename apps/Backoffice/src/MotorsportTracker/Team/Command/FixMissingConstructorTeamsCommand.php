<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Team\Command;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class FixMissingConstructorTeamsCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:constructor-team:fix';

    private const QUERY = <<<'SQL'
INSERT INTO constructor_team (constructor, team)
SELECT DISTINCT ct.constructor, t.id
FROM team t
         JOIN constructor_team ct ON t.ref = (SELECT t2.ref FROM team t2 WHERE t2.id = ct.team)
         LEFT JOIN constructor_team ct_existing ON t.id = ct_existing.team
WHERE ct_existing.team IS NULL;
SQL;

    public function __construct(
        private readonly Connection $connection,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Fix missing constructor-team relations.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $this->connection->execute(SQLQuery::create(self::QUERY));

        $ui->success('Fixed missing constructor-team relations.');

        return Command::SUCCESS;
    }
}
