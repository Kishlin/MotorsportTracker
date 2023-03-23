<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportStatsScrapper\Application\ScrapClassification;

interface ClassificationGateway
{
    public function fetch(string $sessionRef): ClassificationResponse;
}
