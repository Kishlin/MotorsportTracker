<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Event\Command;

use Kishlin\Apps\Backoffice\MotorsportTracker\Shared\Command\CommandRequiringSeasonIdTrait;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\CreateEventStepCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventStep\EventStepCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateStepTypeIfNotExists\CreateStepTypeIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\SearchEvent\SearchEventResponse;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class CreateEventStepCommandUsingSymfony extends SymfonyCommand
{
    use CommandRequiringSeasonIdTrait;

    public const NAME = 'kishlin:motorsport:event-step:add';

    private const ARGUMENT_EVENT = 'event';
    private const QUESTION_EVENT = "Please enter a keyword to find the event.\n";

    private const ARGUMENT_TYPE = 'type';
    private const QUESTION_TYPE = "Please enter a type type for the event step.\n";

    private const ARGUMENT_DATETIME = 'dateTime';
    private const QUESTION_DATETIME = "Please enter a dateTime for the event step.\n";

    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new event step.')
            ->addSeasonIdArguments()
            ->addArgument(self::ARGUMENT_EVENT, InputArgument::OPTIONAL, 'A keyword to find the event')
            ->addArgument(self::ARGUMENT_TYPE, InputArgument::OPTIONAL, 'The type of the event step')
            ->addArgument(self::ARGUMENT_DATETIME, InputArgument::OPTIONAL, 'The date time of the event step')
        ;
    }

    /**
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $seasonId = $this->findSeasonId($input, $output, $ui);
        $eventId  = $this->findEventId($input, $output, $ui, $seasonId);
        $type     = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_TYPE, self::QUESTION_TYPE);
        $dateTime = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_DATETIME, self::QUESTION_DATETIME);

        /** @var StepTypeId $stepTypeIdValueObject */
        $stepTypeIdValueObject = $this->commandBus->execute(CreateStepTypeIfNotExistsCommand::fromScalars($type));
        $stepTypeId            = $stepTypeIdValueObject->value();

        try {
            /** @var EventStepId $uuid */
            $uuid = $this->commandBus->execute(CreateEventStepCommand::fromScalars($eventId, $stepTypeId, $dateTime));
        } catch (EventStepCreationFailureException) {
            $ui->error('Failed to create the event step.');

            return Command::FAILURE;
        }

        $ui->success("Event Step Created: {$uuid}}");

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findEventId(InputInterface $input, OutputInterface $output, SymfonyStyle $ui, string $seasonId): string
    {
        $event = $this->stringFromArgumentsOrPrompt(
            $input,
            $output,
            self::ARGUMENT_EVENT,
            self::QUESTION_EVENT,
        );

        try {
            /** @var SearchEventResponse $eventResponse */
            $eventResponse = $this->queryBus->ask(SearchEventQuery::fromScalars($seasonId, $event));
        } catch (Throwable $e) {
            $ui->error("Failed to find the event with keyword {$event}.");

            throw $e;
        }

        return $eventResponse->eventId()->value();
    }
}
