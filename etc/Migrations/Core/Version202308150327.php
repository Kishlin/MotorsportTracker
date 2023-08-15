<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308150327 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
ALTER TABLE public.country ALTER COLUMN code DROP NOT NULL;
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
ALTER TABLE public.country ALTER code SET NOT NULL;
SQL;
    }
}
