<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportAdmin\Shared\Infrastructure;

use Kishlin\Backend\MotorsportAdmin\Shared\Application\CoreGateway;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CoreRepositoryInterface;

final readonly class CoreRepository extends AbstractRepository implements CoreRepositoryInterface, CoreGateway {}
