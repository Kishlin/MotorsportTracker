<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\BackofficeTests\Helper\Results;

interface ResultsHolderInterface
{
    /**
     * @return array{points: float, position: int, fastestLapTime: string}
     */
    public function getResultsForCar(string $carNumber): array;
}
