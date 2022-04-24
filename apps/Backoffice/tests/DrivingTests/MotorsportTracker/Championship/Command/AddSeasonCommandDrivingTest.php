<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\DrivingTests\MotorsportTracker\Championship\Command;

use Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddSeasonCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeKernelTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship\CreateSeasonDrivingTestCaseTrait;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 * @covers \Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddSeasonCommand
 */
final class AddSeasonCommandDrivingTest extends BackofficeKernelTestCase
{
    use CreateSeasonDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $championship = '63c8b03e-523a-4f16-97b8-0e7e01e0bc06';

        $bus  = $this->getMockForAbstractClass(CommandBus::class);
        $id   = '8ce0b075-d007-41c8-a74e-3a86254f217c';
        $year = 1993;

        $this->mockCreateSeasonCommandHandler($bus, $year, $championship, $id);
        $this->mockService(CommandBus::class, $bus);

        $commandTester = new CommandTester($this->symfonyCommand(AddSeasonCommand::class));

        $commandTester->execute(['year' => $year, 'championship' => $championship]);

        $commandTester->assertCommandIsSuccessful();

        self::assertStringContainsString($id, $commandTester->getDisplay());
    }
}
