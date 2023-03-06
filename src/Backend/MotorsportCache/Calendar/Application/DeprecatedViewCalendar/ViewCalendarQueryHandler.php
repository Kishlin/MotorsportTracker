<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Calendar\Application\DeprecatedViewCalendar;

use Exception;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewCalendarQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ViewCalendarGateway $gateway,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ViewCalendarQuery $query): ViewCalendarResponse
    {
        return ViewCalendarResponse::fromView(
            $this->gateway->view($query->start(), $query->end()),
        );
    }
}
