<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Infrastructure\CommandUsingSymfony;

use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Persistence\SQL\SQLQuery;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PrepareDBCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:persistence:migration:%s:prepare';

    private const CREATE_MIGRATION_TABLE = <<<'SQL'
CREATE TABLE public.migration_version (
    version character varying(255) NOT NULL,
    migrated_on character varying(255) NOT NULL
);
SQL;

    private const PRIMARY_KEY = <<<'SQL'
ALTER TABLE ONLY public.migration_version ADD CONSTRAINT migration_version_pkey PRIMARY KEY (version);
SQL;

    public function __construct(
        private readonly Connection $connection,
        private readonly string $database,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(sprintf(self::NAME, $this->database))
            ->setDescription('Prepare database to use migrations.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $this->connection->execute(SQLQuery::create(self::CREATE_MIGRATION_TABLE));
        $this->connection->execute(SQLQuery::create(self::PRIMARY_KEY));

        $ui->success("Successfully prepared {$this->database} database to use migrations.");

        return Command::SUCCESS;
    }
}
