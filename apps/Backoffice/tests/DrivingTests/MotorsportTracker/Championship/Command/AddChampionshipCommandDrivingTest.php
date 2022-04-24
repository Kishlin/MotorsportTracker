<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\DrivingTests\MotorsportTracker\Championship\Command;

use Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddChampionshipCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeKernelTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship\CreateChampionshipDrivingTestCaseTrait;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 * @covers \Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddChampionshipCommand
 */
final class AddChampionshipCommandDrivingTest extends BackofficeKernelTestCase
{
    use CreateChampionshipDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $bus  = $this->getMockForAbstractClass(CommandBus::class);
        $id   = '8ce0b075-d007-41c8-a74e-3a86254f217c';
        $name = 'Formula 1';
        $slug = 'formulaone';

        $this->mockCreateChampionshipCommandHandler($bus, $name, $slug, $id);
        $this->mockService(CommandBus::class, $bus);

        $commandTester = new CommandTester($this->symfonyCommand(AddChampionshipCommand::class));

        $commandTester->execute(['name' => $name, 'slug' => $slug]);

        $commandTester->assertCommandIsSuccessful();

        self::assertStringContainsString($id, $commandTester->getDisplay());
    }
}
