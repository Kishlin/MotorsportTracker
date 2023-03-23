<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTracker\Result;

use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Kishlin\Backend\MotorsportTracker\Result\Application\CreateClassificationIfNotExists\CreateClassificationIfNotExistsCommand;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Context\MotorsportTrackerContext;
use PHPUnit\Framework\Assert;
use Throwable;

final class ClassificationContext extends MotorsportTrackerContext
{
    private ?UuidValueObject $classificationId = null;
    private ?Throwable $thrownException        = null;

    public function clearGatewaySpies(): void
    {
        self::container()->classificationRepositorySpy()->clear();
    }

    /**
     * @throws Throwable
     */
    #[Given('the classification :name exists')]
    public function theClassificationExists(string $name): void
    {
        self::container()->coreFixtureLoader()->loadFixture("motorsport.result.classification.{$this->format($name)}");
    }

    #[When('a client creates the classification of car number :number in session :session')]
    #[When('a client creates the classification for the same number and session')]
    public function aClientCreatesAClassification(
        int $number = 33,
        string $session = 'motorsport.event.eventSession.australianGrandPrix2022Race'
    ): void {
        $this->classificationId = null;
        $this->thrownException  = null;

        try {
            $sessionId = $this->fixtureId("motorsport.event.eventSession.{$this->format($session)}");

            /** @var UuidValueObject $classificationId */
            $classificationId = self::container()->commandBus()->execute(
                CreateClassificationIfNotExistsCommand::fromScalars(
                    $sessionId,
                    $number,
                    9,
                    20,
                    57,
                    2.0,
                    5710489.0,
                    'CLA',
                    194.319,
                    95068.0,
                    73753.0,
                    1106.0,
                    0,
                    0,
                    42,
                    95068.0,
                    false,
                ),
            );

            $this->classificationId = $classificationId;
        } catch (Throwable $e) {
            $this->thrownException = $e;
        }
    }

    #[Then('the classification is saved')]
    #[Then('the classification is not duplicated')]
    public function theClassificationIsSaved(): void
    {
        Assert::assertCount(1, self::container()->classificationRepositorySpy()->all());

        Assert::assertNotNull($this->classificationId);
        Assert::assertNull($this->thrownException);
        Assert::assertTrue(self::container()->classificationRepositorySpy()->has($this->classificationId));
    }

    #[Then('the id of the classification of car number :number in session :session')]
    public function theIdOfTheClassificationIsReturned(string $session, int $number): void
    {
        Assert::assertNotNull($this->classificationId);
        Assert::assertNull($this->thrownException);

        $entryId = self::container()->entryRepositorySpy()->findForSessionAndNumber(
            new UuidValueObject(self::fixtureId("motorsport.event.eventSession.{$this->format($session)}")),
            new PositiveIntValueObject($number),
        );

        Assert::assertNotNull($entryId);

        Assert::assertTrue(self::container()->classificationRepositorySpy()->safeGet($this->classificationId)->entry()->equals($entryId));
    }
}
