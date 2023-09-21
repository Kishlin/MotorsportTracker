<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Result;

use RuntimeException;

final class ResultIsSuccessException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Cannot unwrap a failure result');
    }
}
