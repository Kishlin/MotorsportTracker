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

final class FixTeamColorsCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:team-color:fix';

    private const QUERY = <<<'SQL'
UPDATE public.team
SET color = '#FFFFFF'::varchar(255)
WHERE ref = '2943bfb0-9957-4430-adc5-121c7975f176'; -- F4 France
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
            ->setDescription('Fix team colors to prevent dark graphs.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $this->connection->execute(SQLQuery::create(self::QUERY));

        $ui->success('Fixed team colors.');

        return Command::SUCCESS;
    }
}
