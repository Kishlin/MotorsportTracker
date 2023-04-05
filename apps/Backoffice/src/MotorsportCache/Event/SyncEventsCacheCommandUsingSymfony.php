<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Event;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\SyncEventCacheCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncEventsCacheCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:events-cache:sync';

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Syncs the events cache.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        try {
            $this->commandBus->execute(SyncEventCacheCommand::create());
        } catch (Throwable) {
            $ui->error('Failed to sync events cache.');

            return Command::FAILURE;
        }

        $ui->success('Successfully synced the events cache.');

        return Command::SUCCESS;
    }
}
