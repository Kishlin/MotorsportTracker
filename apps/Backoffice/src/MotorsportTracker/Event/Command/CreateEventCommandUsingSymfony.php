<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Event\Command;

use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\CreateEventCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\EventCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateEventCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:event:add';

    private const ARGUMENT_SEASON = 'season';
    private const QUESTION_SEASON = "Please enter a season for the event.\n";

    private const ARGUMENT_VENUE = 'venue';
    private const QUESTION_VENUE = "Please enter a venue for the event.\n";

    private const ARGUMENT_INDEX = 'index';
    private const QUESTION_INDEX = "Please enter an index for the event.\n";

    private const ARGUMENT_LABEL = 'label';
    private const QUESTION_LABEL = "Please enter a label for the event.\n";

    public function __construct(
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new event.')
            ->addArgument(self::ARGUMENT_SEASON, InputArgument::OPTIONAL, 'The season of the event')
            ->addArgument(self::ARGUMENT_VENUE, InputArgument::OPTIONAL, 'The venue of the event')
            ->addArgument(self::ARGUMENT_INDEX, InputArgument::OPTIONAL, 'The index of the event')
            ->addArgument(self::ARGUMENT_LABEL, InputArgument::OPTIONAL, 'The label of the event')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $season = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_SEASON, self::QUESTION_SEASON);
        $venue  = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_VENUE, self::QUESTION_VENUE);
        $index  = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_INDEX, self::QUESTION_INDEX);
        $label  = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_LABEL, self::QUESTION_LABEL);

        try {
            /** @var EventId $uuid */
            $uuid = $this->commandBus->execute(CreateEventCommand::fromScalars($season, $venue, $index, $label));
        } catch (EventCreationFailureException) {
            $ui->error('Failed to create the event.');

            return Command::FAILURE;
        }

        $ui->success("Event Created: {$uuid}}");

        return Command::SUCCESS;
    }
}
