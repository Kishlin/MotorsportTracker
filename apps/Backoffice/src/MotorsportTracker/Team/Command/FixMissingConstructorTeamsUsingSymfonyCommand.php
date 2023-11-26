<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Team\Command;

use Kishlin\Backend\MotorsportETL\DataFix\Application\FixMissingConstructorTeams\FixMissingConstructorTeamsCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class FixMissingConstructorTeamsUsingSymfonyCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:constructor-team:fix';

    public function __construct(
        private readonly CommandBus $commandBus,
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

        $this->commandBus->execute(
            FixMissingConstructorTeamsCommand::create(),
        );

        $ui->success('Fixed missing constructor-team relations.');

        return Command::SUCCESS;
    }
}
