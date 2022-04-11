<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Championship;

use Exception;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\ChampionshipCreationFailureException;
use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Championship;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipName;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipSlug;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ChampionshipCreationContext extends MotorsportTrackerContext
{
    private const CHAMPIONSHIP_NAME = 'name';
    private const CHAMPIONSHIP_SLUG = 'slug';

    private ?ChampionshipId $championshipId = null;
    private ?Throwable $thrownException     = null;

    public function clearGatewaySpies(): void
    {
        self::container()->championshipRepositorySpy()->clear();
    }

    /**
     * @Given /^a championship exists$/
     *
     * @throws Exception
     */
    public function aChampionshipExists(): void
    {
        self::container()->championshipRepositorySpy()->save(Championship::create(
            new ChampionshipId(self::CHAMPIONSHIP_ID),
            new ChampionshipName(self::CHAMPIONSHIP_NAME),
            new ChampionshipSlug(self::CHAMPIONSHIP_SLUG),
        ));
    }

    /**
     * @When /^a client creates a new championship$/
     * @When /^a client creates a championship with the same name$/
     */
    public function aClientCreatesANewChampionship(): void
    {
        $this->championshipId  = null;
        $this->thrownException = null;

        try {
            /** @var ChampionshipId $championshipId */
            $championshipId = self::container()->commandBus()->execute(
                CreateChampionshipCommand::fromScalars(self::CHAMPIONSHIP_NAME, self::CHAMPIONSHIP_SLUG),
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
