<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipIfNotExists\CreateChampionshipIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ChampionshipCreationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $championshipId = null;
    private ?Throwable $thrownException      = null;

    public function clearGatewaySpies(): void
    {
        self::container()->championshipRepositorySpy()->clear();
    }

    /**
     * @throws Exception
     */
    #[Given('the championship :name exists')]
    public function theChampionshipExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.championship.series.{$this->format($name)}");
    }

    #[When('a client creates the championship :name with code :code')]
    #[When('a client creates a championship with the same name')]
    public function aClientCreatesTheChampionship(string $name = 'Formula On', string $code = 'formula1'): void
    {
        $this->championshipId  = null;
        $this->thrownException = null;

        try {
            /** @var UuidValueObject $championshipId */
            $championshipId = self::container()->commandBus()->execute(
                CreateChampionshipIfNotExistsCommand::fromScalars($name, null, $code, null),
            );

            $this->championshipId = $championshipId;
        } catch (Throwable $exception) {
            $this->thrownException = $exception;
        }
    }

    #[Then('the championship is saved')]
    #[Then('the championship id is returned')]
    public function theChampionshipIsSaved(): void
    {
        Assert::assertNull($this->thrownException);
        Assert::assertNotNull($this->championshipId);
        Assert::assertTrue(self::container()->championshipRepositorySpy()->has($this->championshipId));
    }
}
