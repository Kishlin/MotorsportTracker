<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Team\Command;

use Kishlin\Backend\Country\Application\CreateCountryIfNotExists\CreateCountryIfNotExistsCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\CreateTeamCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeam\TeamCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateTeamCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:team:add';

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the team:\n";

    private const ARGUMENT_IMAGE = 'image';
    private const QUESTION_IMAGE = "Please enter an image for the team:\n";

    private const ARGUMENT_COUNTRY = 'country';
    private const QUESTION_COUNTRY = "Please enter a country code for the team:\n";

    public function __construct(
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new team.')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the team')
            ->addArgument(self::ARGUMENT_IMAGE, InputArgument::OPTIONAL, 'The image of the team')
            ->addArgument(self::ARGUMENT_COUNTRY, InputArgument::OPTIONAL, 'The country code of the team')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $name        = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $image       = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_IMAGE, self::QUESTION_IMAGE);
        $countryCode = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_COUNTRY, self::QUESTION_COUNTRY);

        /** @var UuidValueObject $countryIdValueObject */
        $countryIdValueObject = $this->commandBus->execute(CreateCountryIfNotExistsCommand::fromScalars($countryCode, $countryCode));
        $countryId            = $countryIdValueObject->value();

        try {
            /** @var TeamId $uuid */
            $uuid = $this->commandBus->execute(CreateTeamCommand::fromScalars($countryId, $image, $name));
        } catch (TeamCreationFailureException) {
            $ui->error('Failed to create the team.');

            return Command::FAILURE;
        }

        $ui->success("Driver Created: {$uuid}}");

        return Command::SUCCESS;
    }
}
