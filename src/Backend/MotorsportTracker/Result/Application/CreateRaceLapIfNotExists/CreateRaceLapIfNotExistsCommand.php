<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRaceLapIfNotExists;

use Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject\TyreDetailsValueObject;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateRaceLapIfNotExistsCommand implements Command
{
    /**
     * @param array{
     *     type: string,
     *     wear: string,
     *     laps: int,
     * }[] $tyreDetails
     */
    private function __construct(
        private string $entry,
        private int $lap,
        private int $position,
        private bool $pit,
        private int $time,
        private ?int $timeToLead,
        private ?int $lapsToLead,
        private ?int $timeToNext,
        private ?int $lapsToNext,
        private array $tyreDetails,
    ) {
    }

    public function entry(): UuidValueObject
    {
        return new UuidValueObject($this->entry);
    }

    public function lap(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->lap);
    }

    public function position(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->position);
    }

    public function pit(): BoolValueObject
    {
        return new BoolValueObject($this->pit);
    }

    public function time(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->time);
    }

    public function timeToLead(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->timeToLead);
    }

    public function lapsToLead(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->lapsToLead);
    }

    public function timeToNext(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->timeToNext);
    }

    public function lapsToNext(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->lapsToNext);
    }

    public function tyreDetails(): TyreDetailsValueObject
    {
        return new TyreDetailsValueObject($this->tyreDetails);
    }

    /**
     * @param array{
     *     type: string,
     *     wear: string,
     *     laps: int,
     * }[] $tyreDetails
     */
    public static function fromScalars(
        string $entry,
        int $lap,
        int $position,
        bool $pit,
        int $time,
        ?int $timeToLead,
        ?int $lapsToLead,
        ?int $timeToNext,
        ?int $lapsToNext,
        array $tyreDetails,
    ): self {
        return new self(
            $entry,
            $lap,
            $position,
            $pit,
            $time,
            $timeToLead,
            $lapsToLead,
            $timeToNext,
            $lapsToNext,
            $tyreDetails,
        );
    }
}
