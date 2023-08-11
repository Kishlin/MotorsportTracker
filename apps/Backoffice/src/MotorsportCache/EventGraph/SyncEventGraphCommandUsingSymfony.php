<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\EventGraph;

use Kishlin\Backend\MotorsportCache\EventGraph\Application\ComputeLapByLapGraph\ComputeLapByLapGraphCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncEventGraphCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-cache:graph-lap-by-lap:compute';

    private const ARGUMENT_EVENT = 'event';
    private const QUESTION_EVENT = "Please enter the id of the event:\n";

    private const MAX_TIME_OPTION = 'max-time-ratio';

    private const MAX_TIME_DESCRIPTION = <<<'TXT'
Max time ratio.
Any lap slower than X% of the fastest lap, X being the given ratio, will be ignored.
By default set to 1.07, which means any laps at least 107% slower than the fastest lap will be ignored.
TXT;

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
            ->addArgument(self::ARGUMENT_EVENT, InputArgument::OPTIONAL, 'The id of the event.')
            ->addOption(self::MAX_TIME_OPTION, 'max', InputOption::VALUE_REQUIRED, self::MAX_TIME_DESCRIPTION)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $event   = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_EVENT, self::QUESTION_EVENT);
        $maxTime = $this->getMaxTimeFromCommandOption($input);

        try {
            $this->commandBus->execute(ComputeLapByLapGraphCommand::fromScalars($event, $maxTime));
        } catch (Throwable $e) {
            $ui->error("Failed to compute lap by lap graph: {$e->getMessage()}");

            echo $e->getTraceAsString();

            return Command::FAILURE;
        }

        $ui->success('Successfully computed lap by lap graph for event.');

        return Command::SUCCESS;
    }

    private function getMaxTimeFromCommandOption(InputInterface $input): ?float
    {
        $maxTime = $input->getOption(self::MAX_TIME_OPTION);
        if (empty($maxTime)) {
            return null;
        }

        assert(is_string($maxTime));

        return (float) $maxTime;
    }
}
