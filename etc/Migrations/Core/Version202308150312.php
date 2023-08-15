<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308150312 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
DROP INDEX uniq_e410c3075e237e06;
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
CREATE UNIQUE INDEX uniq_e410c3075e237e06 ON public.driver USING btree (name);
SQL;
    }
}
