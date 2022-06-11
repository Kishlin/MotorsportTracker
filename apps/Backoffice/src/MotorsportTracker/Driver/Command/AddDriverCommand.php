<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Driver\Command;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\CreateDriverCommand;
use Kishlin\Backend\MotorsportTracker\Driver\Application\CreateDriver\DriverCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Driver\Domain\ValueObject\DriverId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AddDriverCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:driver:add';

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the driver:\n";

    private const ARGUMENT_FIRSTNAME = 'firstname';
    private const QUESTION_FIRSTNAME = "Please enter a firstname for the driver:\n";

    private const ARGUMENT_COUNTRY = 'country';
    private const QUESTION_COUNTRY = "Please enter a country code for the driver:\n";

    public function __construct(
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new driver.')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the driver')
            ->addArgument(self::ARGUMENT_FIRSTNAME, InputArgument::OPTIONAL, 'The firstname of the driver')
            ->addArgument(self::ARGUMENT_COUNTRY, InputArgument::OPTIONAL, 'The country code of the driver')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $name        = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $firstname   = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_FIRSTNAME, self::QUESTION_FIRSTNAME);
        $countryCode = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_COUNTRY, self::QUESTION_COUNTRY);

        /** @var CountryId $countryIdValueObject */
        $countryIdValueObject = $this->commandBus->execute(CreateCountryIfNotExistsCommand::fromScalars($countryCode));
        $countryId            = $countryIdValueObject->value();

        try {
            /** @var DriverId $uuid */
            $uuid = $this->commandBus->execute(CreateDriverCommand::fromScalars($name, $firstname, $countryId));
        } catch (DriverCreationFailureException) {
            $ui->error('A Driver with that firstname/name combination already exists.');

            return Command::FAILURE;
        }

        $ui->text(sprintf("<info>Driver Created: %s</info>\n", $uuid));

        return Command::SUCCESS;
    }
}
