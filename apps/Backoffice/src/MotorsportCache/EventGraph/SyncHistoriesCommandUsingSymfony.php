<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\EventGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeHistoriesForEvent\ComputeHistoriesForEventCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncHistoriesCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:histories:compute';

    private const ARGUMENT_EVENT = 'event';
    private const QUESTION_EVENT = "Please enter the id of the event:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Computes histories for all races of an event.')
            ->addArgument(self::ARGUMENT_EVENT, InputArgument::OPTIONAL, 'The id of the event.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $event = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_EVENT, self::QUESTION_EVENT);

        try {
            $this->commandBus->execute(ComputeHistoriesForEventCommand::fromScalars($event));
        } catch (Throwable $e) {
            $ui->error("Failed to compute histories: {$e->getMessage()}");

            echo $e->getTraceAsString();

            return Command::FAILURE;
        }

        $ui->success('Successfully histories for event.');

        return Command::SUCCESS;
    }
}
