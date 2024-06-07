<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Analytics\Domain\DTO;

use JsonException;

final readonly class AnalyticsForSeasonDTO
{
    /**
     * @param array<int, array<string, null|array<string, mixed>|bool|float|int|string>> $data
     */
    private function __construct(
        private array $data,
    ) {}

    /**
     * @return array<int, array<string, null|array<string, mixed>|bool|float|int|string>>
     */
    public function analytics(): array
    {
        return $this->data;
    }

    /**
     * @param array<int, array<string, null|array<string, mixed>|bool|float|int|string>> $data
     *
     * @throws JsonException
     */
    public static function fromData(array $data): self
    {
        $formattedData = [];

        foreach ($data as $rawResult) {
            $formattedResult = $rawResult;

            if (null !== $rawResult['country']) {
                assert(is_string($rawResult['country']));

                /** @var array<string, mixed> $decodedCountry */
                $decodedCountry = json_decode($rawResult['country'], true, 512, JSON_THROW_ON_ERROR);

                $formattedResult['country'] = $decodedCountry;
            }

            $formattedData[] = $formattedResult;
        }

        return new self($formattedData);
    }
}
