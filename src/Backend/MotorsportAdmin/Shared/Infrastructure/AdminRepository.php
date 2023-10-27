<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\AdminGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\AdminRepositoryInterface;

final readonly class AdminRepository extends AbstractRepository implements AdminRepositoryInterface, AdminGateway
{
}
