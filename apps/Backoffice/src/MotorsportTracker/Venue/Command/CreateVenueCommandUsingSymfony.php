<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Venue\Command;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\CreateVenueCommand;
use Kishlin\Backend\MotorsportTracker\Venue\Application\CreateVenue\VenueCreationFailureException;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateVenueCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:venue:add';

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the venue:\n";

    private const ARGUMENT_SLUG = 'slug';
    private const QUESTION_SLUG = "Please enter a slug for the venue:\n";

    private const ARGUMENT_COUNTRY = 'country';
    private const QUESTION_COUNTRY = "Please enter a country code for the venue:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new venue.')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the venue')
            ->addArgument(self::ARGUMENT_SLUG, InputArgument::OPTIONAL, 'The slug of the venue')
            ->addArgument(self::ARGUMENT_COUNTRY, InputArgument::OPTIONAL, 'The country code of the venue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $name        = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $slug        = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_SLUG, self::QUESTION_SLUG);
        $countryCode = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_COUNTRY, self::QUESTION_COUNTRY);

        /** @var UuidValueObject $countryIdValueObject */
        $countryIdValueObject = $this->commandBus->execute(CreateCountryIfNotExistsCommand::fromScalars($countryCode, $countryCode));
        $countryId            = $countryIdValueObject->value();

        try {
            /** @var UuidValueObject $uuid */
            $uuid = $this->commandBus->execute(CreateVenueCommand::fromScalars($name, $slug, $countryId));
        } catch (VenueCreationFailureException) {
            $ui->error('A Venue with that name already exists.');

            return Command::FAILURE;
        }

        $ui->success("Venue Created: {$uuid}}");

        return Command::SUCCESS;
    }
}
