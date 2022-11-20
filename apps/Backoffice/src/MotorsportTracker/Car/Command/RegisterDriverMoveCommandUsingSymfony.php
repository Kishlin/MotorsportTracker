<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Car\Command;

use DateTimeImmutable;
use Exception;
use Kishlin\Backend\MotorsportTracker\Car\Application\RecordDriverMove\RecordDriverMoveCommand;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarQuery;
use Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar\SearchCarResponse;
use Kishlin\Backend\MotorsportTracker\Car\Domain\ValueObject\DriverMoveId;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverQuery;
use Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver\SearchDriverResponse;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class RegisterDriverMoveCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:driver-move:add';

    private const ARGUMENT_DRIVER = 'driver';
    private const QUESTION_DRIVER = "Please enter a keyword to find the driver:\n";

    private const ARGUMENT_CHAMPIONSHIP = 'championship';
    private const QUESTION_CHAMPIONSHIP = "Please enter a keyword to find the championship:\n";

    private const ARGUMENT_YEAR = 'year';
    private const QUESTION_YEAR = "Please enter the year of the season:\n";

    private const ARGUMENT_TEAM = 'team';
    private const QUESTION_TEAM = "Please enter a keyword to find the team:\n";

    private const ARGUMENT_NUMBER = 'number';
    private const QUESTION_NUMBER = "Please enter the number of the car:\n";

    private const ARGUMENT_DATETIME = 'datetime';
    private const QUESTION_DATETIME = "Please enter the datetime of the driver move:\n";

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
            ->addArgument(self::ARGUMENT_YEAR, InputArgument::OPTIONAL, 'The year of the season')
            ->addArgument(self::ARGUMENT_TEAM, InputArgument::OPTIONAL, 'A keyword to find the team')
            ->addArgument(self::ARGUMENT_NUMBER, InputArgument::OPTIONAL, 'The number of the car')
            ->addArgument(self::ARGUMENT_DATETIME, InputArgument::OPTIONAL, 'The date time of the driver move')
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
            $carId    = $this->findCar($input, $output, $ui);
        } catch (Throwable) {
            return Command::FAILURE;
        }

        $dateTime = new DateTimeImmutable(
            $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_DATETIME, self::QUESTION_DATETIME),
        );

        try {
            /** @var DriverMoveId $uuid */
            $uuid = $this->commandBus->execute(RecordDriverMoveCommand::fromScalars($carId, $driverId, $dateTime));
        } catch (Throwable $e) {
            $ui->error('Failed to register the driver move with these parameters.');

            return Command::FAILURE;
        }

        $ui->success("Driver Move Created: {$uuid}");

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findCar(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $championship = $this->stringFromArgumentsOrPrompt(
            $input,
            $output,
            self::ARGUMENT_CHAMPIONSHIP,
            self::QUESTION_CHAMPIONSHIP,
        );

        $year   = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_YEAR, self::QUESTION_YEAR);
        $team   = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_TEAM, self::QUESTION_TEAM);
        $number = $this->intFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NUMBER, self::QUESTION_NUMBER);

        try {
            /** @var SearchCarResponse $carResponse */
            $carResponse = $this->queryBus->ask(SearchCarQuery::fromScalars($number, $team, $championship, $year));
        } catch (Throwable $e) {
            $ui->error("Failed to find the car {$number} of {$team} during {$championship} {$year}.");

            throw $e;
        }

        return $carResponse->carId()->value();
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
