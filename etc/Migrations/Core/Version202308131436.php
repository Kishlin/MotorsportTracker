<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308131436 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
ALTER TABLE public.race_lap ALTER COLUMN tyre_details type jsonb using tyre_details::jsonb;
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
ALTER TABLE public.race_lap ALTER COLUMN tyre_details type json using tyre_details::json;
SQL;
    }
}
