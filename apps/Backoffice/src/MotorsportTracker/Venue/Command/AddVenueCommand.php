<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Venue\Command;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Venue\Domain\ValueObject\VenueId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class AddVenueCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:venue:add';

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the venue:\n";

    private const ARGUMENT_COUNTRY = 'country';
    private const QUESTION_COUNTRY = "Please enter a country code for the venue:\n";

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
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the venue')
            ->addArgument(self::ARGUMENT_COUNTRY, InputArgument::OPTIONAL, 'The country code of the venue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $name        = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $countryCode = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_COUNTRY, self::QUESTION_COUNTRY);

        /** @var CountryId $countryIdValueObject */
        $countryIdValueObject = $this->commandBus->execute(CreateCountryIfNotExistsCommand::fromScalars($countryCode));
        $countryId            = $countryIdValueObject->value();

        try {
            /** @var VenueId $uuid */
            $uuid = $this->commandBus->execute(CreateVenueCommand::fromScalars($name, $countryId));
        } catch (VenueCreationFailureException) {
            $ui->error('A Venue with that name already exists.');

            return Command::FAILURE;
        }

        $ui->success("Venue Created: {$uuid}}");

        return Command::SUCCESS;
    }
}
