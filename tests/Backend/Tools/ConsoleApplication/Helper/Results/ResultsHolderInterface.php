<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\ConsoleApplication\Helper\Results;

interface ResultsHolderInterface
{
    /**
     * @return array{points: float, position: string}
     */
    public function getResultsForCar(string $carNumber): array;
}
