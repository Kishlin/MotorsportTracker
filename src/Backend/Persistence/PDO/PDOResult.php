<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\PDO;

use Kishlin\Backend\Persistence\Core\Result\Result;
use Kishlin\Backend\Persistence\Core\Result\ResultFailure;
use Kishlin\Backend\Persistence\Core\Result\ResultIsNotAFailureException;
use Kishlin\Backend\Persistence\Core\Result\ResultIsNotASuccessException;
use PDO;
use PDOStatement;

final class PDOResult implements Result
{
    private function __construct(
        private readonly ?PDOStatement $statement,
        private readonly bool $isOk,
        private readonly ?ResultFailure $failure,
    ) {
    }

    public function isOk(): bool
    {
        return true === $this->isOk;
    }

    public function isFail(): bool
    {
        return false === $this->isOk;
    }

    public function failMessage(): ResultFailure
    {
        if (null === $this->failure) {
            throw new ResultIsNotAFailureException();
        }

        return $this->failure;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAllAssociative(): array
    {
        if (null === $this->statement) {
            throw new ResultIsNotASuccessException();
        }

        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAssociative(): array
    {
        if (null === $this->statement) {
            throw new ResultIsNotASuccessException();
        }

        $ret = $this->statement->fetch(PDO::FETCH_ASSOC);
        if (false === $ret) {
            return [];
        }

        return $ret;
    }

    public static function ok(PDOStatement $statement): self
    {
        return new self($statement, true, null);
    }

    public static function fail(ResultFailure $resultFailure): self
    {
        return new self(null, false, $resultFailure);
    }
}