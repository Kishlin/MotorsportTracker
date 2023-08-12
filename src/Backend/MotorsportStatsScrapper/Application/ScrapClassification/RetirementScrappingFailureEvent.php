<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

use Kishlin\Backend\Shared\Domain\Bus\Event\Event;
use Throwable;

final readonly class RetirementScrappingFailureEvent implements Event
{
    /**
     * @param array{
     *     driver: array{
     *         name: string,
     *         firstName: string,
     *         lastName: string,
     *         shortCode: string,
     *         colour: null|string,
     *         uuid: string,
     *         picture: string,
     *     },
     *     carNumber: string,
     *     reason: string,
     *     type: string,
     *     dns: bool,
     *     lap: int,
     *     details: null,
     * } $retirement
     */
    private function __construct(
        private array $retirement,
        private Throwable $throwable,
    ) {
    }

    /**
     * @return array{
     *     driver: array{
     *         name: string,
     *         firstName: string,
     *         lastName: string,
     *         shortCode: string,
     *         colour: null|string,
     *         uuid: string,
     *         picture: string,
     *     },
     *     carNumber: string,
     *     reason: string,
     *     type: string,
     *     dns: bool,
     *     lap: int,
     *     details: null,
     * } $event
     */
    public function retirement(): array
    {
        return $this->retirement;
    }

    public function throwable(): Throwable
    {
        return $this->throwable;
    }

    /**
     * @param array{
     *     driver: array{
     *         name: string,
     *         firstName: string,
     *         lastName: string,
     *         shortCode: string,
     *         colour: null|string,
     *         uuid: string,
     *         picture: string,
     *     },
     *     carNumber: string,
     *     reason: string,
     *     type: string,
     *     dns: bool,
     *     lap: int,
     *     details: null,
     * } $retirement
     */
    public static function forRetirement(array $retirement, Throwable $e): self
    {
        return new self($retirement, $e);
    }
}
