<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\ChampionshipCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ChampionshipCreationContext extends MotorsportTrackerContext
{
    private ?ChampionshipId $championshipId = null;
    private ?Throwable $thrownException     = null;

    public function clearGatewaySpies(): void
    {
        self::container()->championshipRepositorySpy()->clear();
    }

    /**
     * @Given the championship :name exists
     *
     * @throws Exception
     */
    public function theChampionshipExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.championship.championship.{$this->format($name)}");
    }

    /**
     * @When a client creates the championship :name with slug :slug
     * @When /^a client creates a championship with the same name$/
     */
    public function aClientCreatesTheChampionship(string $name = 'Formula On', string $slug = 'formula1'): void
    {
        $this->championshipId  = null;
        $this->thrownException = null;

        try {
            /** @var ChampionshipId $championshipId */
            $championshipId = self::container()->commandBus()->execute(
                CreateChampionshipCommand::fromScalars($name, $slug),
            );

            $this->championshipId = $championshipId;
        } catch (Throwable $exception) {
            $this->thrownException = $exception;
        }
    }

    /**
     * @Then /^the championship is saved$/
     */
    public function theChampionshipIsSaved(): void
    {
        Assert::assertNotNull($this->championshipId);
        Assert::assertTrue(self::container()->championshipRepositorySpy()->has($this->championshipId));
    }

    /**
     * @Then /^the championship creation is declined$/
     */
    public function theChampionshipCreationIsDeclined(): void
    {
        Assert::assertNotNull($this->thrownException);
        Assert::assertInstanceOf(ChampionshipCreationFailureException::class, $this->thrownException);
    }
}
