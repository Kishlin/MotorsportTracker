<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Persistence\Command;

use Kishlin\Backend\Persistence\Migration\Migrator;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ApplyMigrationCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:persistence:migration:%s:apply';

    public const OPTION_UP      = 'up';
    public const OPTION_DOWN    = 'down';
    public const OPTION_VERSION = 'migration';

    public function __construct(
        private readonly Migrator $migrator,
        private readonly string $database,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(sprintf(self::NAME, $this->database))
            ->setDescription('Apply migrations to the database.')
            ->addOption(self::OPTION_UP, null, InputOption::VALUE_NONE, 'Apply new migration. Default behavior.')
            ->addOption(self::OPTION_DOWN, null, InputOption::VALUE_NONE, 'Unapply migration.')
            ->addOption(self::OPTION_VERSION, null, InputOption::VALUE_REQUIRED, 'The target migration version', '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $up   = $input->getOption(self::OPTION_UP);
        $down = $input->getOption(self::OPTION_DOWN);

        if ($up && $down) {
            $ui->error('You cannot up and down migrations at the same time.');

            return Command::INVALID;
        }

        $version = $input->getOption(self::OPTION_VERSION);
        assert(null === $version || is_string($version));

        if ($down) {
            empty($version) ? $this->migrator->downALl() : $this->migrator->downOne($version);
        } else {
            empty($version) ? $this->migrator->upALl() : $this->migrator->upOne($version);
        }

        return Command::SUCCESS;
    }
}
