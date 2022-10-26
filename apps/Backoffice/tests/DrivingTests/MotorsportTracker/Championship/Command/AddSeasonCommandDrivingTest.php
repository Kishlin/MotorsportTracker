<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\DrivingTests\MotorsportTracker\Championship\Command;

use Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddSeasonCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ChampionshipPOPO;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\Backoffice\Tools\BackofficeKernelTestCase;
use Kishlin\Tests\Apps\Backoffice\Tools\CommandTest\CommandWithChoiceQuestionTrait;
use Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship\CreateSeasonDrivingTestCaseTrait;
use Kishlin\Tests\Backend\Apps\DrivingTests\MotorsportTracker\Championship\ViewAllChampionshipsDrivingTestCaseTrait;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 * @covers \Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command\AddSeasonCommand
 */
final class AddSeasonCommandDrivingTest extends BackofficeKernelTestCase
{
    use CommandWithChoiceQuestionTrait;
    use CreateSeasonDrivingTestCaseTrait;
    use ViewAllChampionshipsDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $championshipId = '63c8b03e-523a-4f16-97b8-0e7e01e0bc06';
        $championships  = [
            ChampionshipPOPO::fromScalars($championshipId, 'Championship'),
        ];

        $seasonId   = '8ce0b075-d007-41c8-a74e-3a86254f217c';
        $seasonYear = 1993;

        $commandBus = $this->getMockForAbstractClass(CommandBus::class);
        $queryBus   = $this->getMockForAbstractClass(QueryBus::class);

        $this->mockCreateSeasonCommandHandler($commandBus, $seasonYear, $championshipId, $seasonId);
        $this->mockViewAllChampionshipsQueryHandler($queryBus, $championships);
        $this->mockService(CommandBus::class, $commandBus);
        $this->mockService(QueryBus::class, $queryBus);

        $command       = $this->symfonyCommand(AddSeasonCommand::class);
        $commandTester = new CommandTester($command);

        $this->pickFirstChoiceInAnyChoiceQuestion($command);

        $commandTester->execute(['year' => $seasonYear]);

        $commandTester->assertCommandIsSuccessful();

        self::assertStringContainsString($seasonId, $commandTester->getDisplay());
    }
}
