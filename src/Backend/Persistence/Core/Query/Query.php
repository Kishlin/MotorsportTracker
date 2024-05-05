<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Core\Query;

final readonly class Query
{
    public function __construct(
        /** @var array<int, string> */
        public array $selects = [],

        public ?string $from = null,

        /** @var array<int, string> */
        public array $joins = [],

        /** @var array<int, string> */
        public array $wheres = [],

        /** @var array<int, string> */
        public array $groupBys = [],

        /** @var array<int, string> */
        public array $orderBys = [],

        public ?int $limit = null,

        /** @var array<string, null|bool|float|int|string> */
        public array $params = [],
    ) {
    }
}
