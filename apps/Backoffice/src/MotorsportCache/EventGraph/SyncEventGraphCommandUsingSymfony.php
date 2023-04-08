<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\EventGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\ComputeLapByLapGraphCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncEventGraphCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:graph-lap-by-lap:compute';

    private const ARGUMENT_EVENT = 'event';
    private const QUESTION_EVENT = "Please enter the id of the event:\n";

    private const ARGUMENT_MAX_LAP_TIME = 'max-laptime';
    private const QUESTION_MAX_LAP_TIME = "Please enter the maximum lap time:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Computes results for all races of an event.')
            ->addArgument(self::ARGUMENT_EVENT, InputArgument::OPTIONAL, 'The id of the event')
            ->addArgument(self::ARGUMENT_MAX_LAP_TIME, InputArgument::OPTIONAL, 'The maximum lap time')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $event = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_EVENT, self::QUESTION_EVENT);
        $max   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_MAX_LAP_TIME, self::QUESTION_MAX_LAP_TIME);

        try {
            $graphId = $this->commandBus->execute(ComputeLapByLapGraphCommand::fromScalars($event, $max));

            assert(null === $graphId || $graphId instanceof UuidValueObject);
        } catch (Throwable $e) {
            $ui->error("Failed to compute lap by lap graph for this event: {$e->getMessage()}");

            echo $e->getTraceAsString();

            return Command::FAILURE;
        }

        if (null === $graphId) {
            $ui->info("Did not create the lap by lap graph for event {$event}");
        } else {
            $ui->success("Successfully computed lap by lap graph for event #{$graphId->value()}.");
        }

        return Command::SUCCESS;
    }
}
