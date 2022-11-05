<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaverUsingDoctrine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LoadFixtureCommandUsingSymfony extends Command
{
    private const NAME = 'kishlin:motorsport:fixtures:load';

    private const ARGUMENT_FIXTURES = 'fixtures';

    private FixtureLoader $fixtureLoader;

    public function __construct(
        EntityManagerInterface $entityManager,
        UuidGenerator $uuidGenerator,
    ) {
        parent::__construct();

        $fixtureSaver = new FixtureSaverUsingDoctrine($entityManager);

        CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
        MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);

        $this->fixtureLoader = new FixtureLoader(
            $uuidGenerator,
            $fixtureSaver,
            __DIR__ . '/../../../../../../../etc/Fixtures',
        );
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Loads the required fixtures.')
            ->addArgument(
                self::ARGUMENT_FIXTURES,
                InputArgument::REQUIRED,
                'The names of the fixtures, separated by `;`.',
            )
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fixtures = $input->getArgument(self::ARGUMENT_FIXTURES);
        assert(is_string($fixtures));

        foreach (explode(';', $fixtures) as $fixture) {
            $this->fixtureLoader->loadFixture($fixture);
        }

        return Command::SUCCESS;
    }
}
