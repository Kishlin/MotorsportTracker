<?php

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Command\Symfony;

use Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Probe;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

abstract class AbstractCheckHealthCommand extends Command
{
    /** @var Probe[] */
    protected iterable $probes;

    /**
     * @param Probe[] $probes
     */
    public function __construct(
        #[TaggedIterator('kishlin.shared.infrastructure.monitoring.probe')]
        iterable $probes,
        private readonly string $env,
    ) {
        parent::__construct();

        $this->probes = $probes;
    }

    protected function configure(): void
    {
        $this->setName('kishlin:monitoring:check-health');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->servicesHealth();

        $output->writeln(json_encode($data, JSON_PRETTY_PRINT));

        return 0;
    }

    /**
     * @return array<string, bool|string>
     */
    protected function servicesHealth(): iterable
    {
        $data = [];

        foreach ($this->probes as $probe) {
            $data[$probe->name()] = $probe->isAlive();
        }

        $data['Environment'] = $this->env;

        return $data;
    }
}