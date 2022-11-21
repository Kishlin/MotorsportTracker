<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Racer\Command;

use Exception;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQuery;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverResponse;
use Kishlin\Backend\MotorsportTracker\Racer\Application\UpdateRacerEndDate\UpdateRacerEndDateCommand;
use Kishlin\Backend\MotorsportTracker\Racer\Domain\ValueObject\RacerId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class ChangeRacerEndDateCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:racer:change';

    private const ARGUMENT_DRIVER = 'driver';
    private const QUESTION_DRIVER = "Please enter a keyword to find the driver:\n";

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter a keyword to find the championship:\n";

    private const ARGUMENT_END_DATE = 'endDate';
    private const QUESTION_END_DATE = "Please enter the datetime on which the racer now ends:\n";

    public function __construct(
        private QueryBus $queryBus,
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Records a driver move.')
            ->addArgument(self::ARGUMENT_DRIVER, InputArgument::OPTIONAL, 'A keyword to find the driver')
            ->addArgument(self::ARGUMENT_CHAMPIONSHIP, InputArgument::OPTIONAL, 'A keyword to find the championship')
            ->addArgument(self::ARGUMENT_END_DATE, InputArgument::OPTIONAL, 'The end date time of the racer')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        try {
            $driverId = $this->findDriver($input, $output, $ui);
        } catch (Throwable) {
            return Command::FAILURE;
        }

        $championship = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_CHAMPIONSHIP, self::QUESTION_CHAMPIONSHIP);
        $newEndDate   = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_END_DATE, self::QUESTION_END_DATE);

        try {
            /** @var RacerId $racerId */
            $racerId = $this->commandBus->execute(
                UpdateRacerEndDateCommand::fromScalars($driverId, $championship, $newEndDate),
            );
        } catch (Throwable $e) {
            $ui->error('Failed to update the racer with these parameters.');

            return Command::FAILURE;
        }

        $ui->success("Racer Updated: {$racerId}");

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findDriver(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $driver = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_DRIVER, self::QUESTION_DRIVER);

        try {
            /** @var SearchDriverResponse $driverResponse */
            $driverResponse = $this->queryBus->ask(SearchDriverQuery::fromScalars($driver));
        } catch (Throwable $e) {
            $ui->error("Failed to find the driver with keyword {$driver}.");

            throw $e;
        }

        return $driverResponse->driverId()->value();
    }
}
