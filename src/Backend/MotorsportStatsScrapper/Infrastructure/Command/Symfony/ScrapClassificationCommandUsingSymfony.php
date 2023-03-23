<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Infrastructure\Command\Symfony;

use Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification\ScrapClassificationCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ScrapClassificationCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport-stats:classification:scrap';

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter the name of the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    private const ARGUMENT_EVENT = 'event';
    private const QUESTION_EVENT = "Please enter the name of the event:\n";

    private const ARGUMENT_TYPE = 'type';
    private const QUESTION_TYPE = "Please enter the type of the session:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrap classification for a session.')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'The series name')
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The season year')
            ->addArgument(self::ARGUMENT_EVENT, InputArgument::OPTIONAL, 'The event name')
            ->addArgument(self::ARGUMENT_TYPE, InputArgument::OPTIONAL, 'The session type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $series  = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $year    = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);
        $event   = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_EVENT, self::QUESTION_EVENT);
        $session = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_TYPE, self::QUESTION_TYPE);

        $this->commandBus->execute(ScrapClassificationCommand::fromScalars($series, $year, $event, $session));

        $ui->success("Finished scrapping classifications for {$series} #{$year} {$event} {$session}.");

        return Command::SUCCESS;
    }
}
