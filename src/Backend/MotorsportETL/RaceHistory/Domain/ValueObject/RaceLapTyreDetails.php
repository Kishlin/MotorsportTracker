<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Domain\ValueObject;

use JsonException;
use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class RaceLapTyreDetails implements Mapped
{
    /**
     * @param array{type: string, wear: string, laps: int}[] $tyreDetails
     */
    private function __construct(
        private array $tyreDetails,
    ) {}

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        $data = json_encode($this->tyreDetails, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);

        return [
            'tyre_details' => $data,
        ];
    }

    /**
     * @param array{type: string, wear: string, laps: int}[] $tyreDetails
     */
    public static function fromData(array $tyreDetails): self
    {
        return new self(
            $tyreDetails,
        );
    }
}
