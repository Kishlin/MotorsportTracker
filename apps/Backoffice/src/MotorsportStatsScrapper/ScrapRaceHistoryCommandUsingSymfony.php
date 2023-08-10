<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportStatsScrapper;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapRaceHistory\ScrapRaceHistoryCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapRaceHistoryCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-stats:race-history:scrap';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    private const OPTION_EVENT = 'event';

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap race history for a session.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name.')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The season year.')
            ->addOption(self::OPTION_EVENT, null, InputOption::VALUE_OPTIONAL, 'The event name. Leave empty to scrap the whole season.', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);
        $event  = $this->getEventFromCommandOption($input);

        $this->commandBus->execute(ScrapRaceHistoryCommand::fromScalars($series, $year, $event));

        $ui->success("Finished scrapping race history for {$series} #{$year} {$event}.");

        return Command::SUCCESS;
    }

    private function getEventFromCommandOption(InputInterface $input): ?string
    {
        $event = $input->getOption(self::OPTION_EVENT);
        if (empty($event)) {
            $event = null;
        }

        assert(null === $event || is_string($event));

        return $event;
    }
}
