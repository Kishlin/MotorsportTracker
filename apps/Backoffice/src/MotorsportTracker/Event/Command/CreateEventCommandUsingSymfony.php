<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Event\Command;

use DateTimeImmutable;
use Kishlin\Apps\Backoffice\MotorsportTracker\Shared\Command\CommandRequiringSeasonIdTrait;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\CreateEventCommand;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEvent\EventCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueQuery;
use Kishlin\Backend\MotorsportTracker\Venue\Application\SearchVenue\SearchVenueResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class CreateEventCommandUsingSymfony extends SymfonyCommand
{
    use CommandRequiringSeasonIdTrait;

    public const NAME = 'kishlin:motorsport:event:add';

    private const ARGUMENT_VENUE = 'venue';
    private const QUESTION_VENUE = "Please enter a keyword to find the venue for the event.\n";

    private const ARGUMENT_INDEX = 'index';
    private const QUESTION_INDEX = "Please enter an index for the event.\n";

    private const ARGUMENT_SLUG = 'slug';
    private const QUESTION_SLUG = "Please enter a slug for the event.\n";

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the event.\n";

    private const ARGUMENT_SHORT_NAME = 'short-name';
    private const QUESTION_SHORT_NAME = "Please enter a short name for the event.\n";

    private const ARGUMENT_START_DATE = 'start-date';
    private const QUESTION_START_DATE = "Please enter a start date for the event.\n";

    private const ARGUMENT_END_DATE = 'end-date';
    private const QUESTION_END_DATE = "Please enter a end date for the event.\n";

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new event.')
            ->addSeasonIdArguments()
            ->addArgument(self::ARGUMENT_VENUE, InputArgument::OPTIONAL, 'A keyword to find the venue of the event')
            ->addArgument(self::ARGUMENT_INDEX, InputArgument::OPTIONAL, 'The index of the event')
            ->addArgument(self::ARGUMENT_SLUG, InputArgument::OPTIONAL, 'The slug of the event')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the event')
            ->addArgument(self::ARGUMENT_SHORT_NAME, InputArgument::OPTIONAL, 'The short name of the event')
            ->addArgument(self::ARGUMENT_START_DATE, InputArgument::OPTIONAL, 'The start date of the event')
            ->addArgument(self::ARGUMENT_END_DATE, InputArgument::OPTIONAL, 'The end date of the event')
        ;
    }

    /**
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $seasonId  = $this->findSeasonId($input, $output, $ui);
        $venueId   = $this->findVenueId($input, $output, $ui);
        $index     = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_INDEX, self::QUESTION_INDEX);
        $slug      = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_SLUG, self::QUESTION_SLUG);
        $name      = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $shortName = $this->nullableStringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_SHORT_NAME, self::QUESTION_SHORT_NAME);
        $startDate = $this->nullableStringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_START_DATE, self::QUESTION_START_DATE);
        $endDate   = $this->nullableStringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_END_DATE, self::QUESTION_END_DATE);

        try {
            /** @var UuidValueObject $uuid */
            $uuid = $this->commandBus->execute(
                CreateEventCommand::fromScalars(
                    $seasonId,
                    $venueId,
                    $index,
                    $slug,
                    $name,
                    $shortName,
                    empty($startDate) ? null : new DateTimeImmutable($startDate),
                    empty($endDate) ? null : new DateTimeImmutable($endDate),
                ),
            );
        } catch (EventCreationFailureException) {
            $ui->error('Failed to create the event.');

            return Command::FAILURE;
        }

        $ui->success("Event Created: {$uuid}}");

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findVenueId(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $venue = $this->stringFromArgumentsOrPrompt(
            $input,
            $output,
            self::ARGUMENT_VENUE,
            self::QUESTION_VENUE,
        );

        try {
            /** @var SearchVenueResponse $venueResponse */
            $venueResponse = $this->queryBus->ask(SearchVenueQuery::fromScalar($venue));
        } catch (Throwable $e) {
            $ui->error("Failed to find the venue with keyword {$venue}.");

            throw $e;
        }

        return $venueResponse->venueId()->value();
    }
}
