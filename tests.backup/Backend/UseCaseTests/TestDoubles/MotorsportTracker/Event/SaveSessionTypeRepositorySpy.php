<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\SaveSessionTypeGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateSessionTypeIfNotExists\SessionTypeIdForLabelGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property SessionType[] $objects
 *
 * @method SessionType[]    all()
 * @method null|SessionType get(UuidValueObject $id)
 * @method SessionType      safeGet(UuidValueObject $id)
 */
final class SaveSessionTypeRepositorySpy extends AbstractRepositorySpy implements SaveSessionTypeGateway, SessionTypeIdForLabelGateway
{
    /**
     * @throws Exception
     */
    public function save(SessionType $sessionType): void
    {
        if ($this->labelAlreadyTaken($sessionType)) {
            throw new Exception();
        }

        $this->objects[$sessionType->id()->value()] = $sessionType;
    }

    public function idForLabel(StringValueObject $label): ?UuidValueObject
    {
        foreach ($this->objects as $savedEventSession) {
            if ($savedEventSession->label()->equals($label)) {
                return $savedEventSession->id();
            }
        }

        return null;
    }

    private function labelAlreadyTaken(SessionType $sessionType): bool
    {
        foreach ($this->objects as $savedSessionType) {
            if ($savedSessionType->label()->equals($sessionType->label())) {
                return true;
            }
        }

        return false;
    }
}
