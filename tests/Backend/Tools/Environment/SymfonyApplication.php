<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Environment;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kishlin\Backend\Country\Shared\Infrastructure\Persistence\Fixtures\CountryFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportCache\Shared\Infrastructure\Persistence\Fixtures\MotorsportCacheFixtureConverterConfigurator;
use Kishlin\Backend\MotorsportTracker\Shared\Infrastructure\Persistence\Fixtures\MotorsportTrackerFixtureConverterConfigurator;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureLoader;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Fixtures\FixtureSaverUsingDoctrine;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\Tools\ConsoleApplication\ConsoleApplicationInterface;
use Kishlin\Tests\Backend\Tools\ConsoleApplication\SymfonyConsoleApplication;
use Kishlin\Tests\Backend\Tools\Database\DatabaseInterface;
use Kishlin\Tests\Backend\Tools\Database\PostgresDatabase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class SymfonyApplication
{
    private ?KernelInterface $kernel = null;

    /** @var DatabaseInterface[] */
    private array $databases = [];

    private ?ConsoleApplicationInterface $application = null;

    /**
     * @param class-string<KernelInterface> $kernelClass
     */
    public function __construct(private readonly string $kernelClass)
    {
    }

    public function clearEnvironment(): void
    {
        $this->application = null;
        $this->kernel      = null;
        $this->databases   = [];
    }

    public function application(): ConsoleApplicationInterface
    {
        if (null === $this->application) {
            $this->application = new SymfonyConsoleApplication($this->kernel());
        }

        return $this->application;
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        return $this->kernel()->handle($request);
    }

    public function database(string $database = 'core'): DatabaseInterface
    {
        if (false === array_key_exists($database, $this->databases)) {
            $service = "kishlin.app.infrastructure.entity_manager.{$database}";

            $entityManager = self::kernel()->getContainer()->get($service);
            assert($entityManager instanceof EntityManagerInterface);

            $fixtureSaver = new FixtureSaverUsingDoctrine($entityManager);

            CountryFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
            MotorsportCacheFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);
            MotorsportTrackerFixtureConverterConfigurator::populateFixtureSaverWithConverters($fixtureSaver);

            $fixtureLoader = new FixtureLoader(new UuidGeneratorUsingRamsey(), $fixtureSaver, '/app/etc/Fixtures/' . ucfirst($database));

            $this->databases[$database] = new PostgresDatabase($entityManager, $fixtureLoader);
        }

        return $this->databases[$database];
    }

    private function kernel(): KernelInterface
    {
        if (null === $this->kernel) {
            (new Dotenv())->bootEnv(__DIR__ . '/../../../../.env.acceptance');

            $this->kernel = new $this->kernelClass('acceptance', true);
            $this->kernel->boot();
        }

        return $this->kernel;
    }
}
