<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308121427 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
ALTER TABLE driver ADD COLUMN country character varying(36) DEFAULT NULL::character varying;
ALTER TABLE entry DROP COLUMN country;
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
ALTER TABLE driver DROP COLUMN country;
ALTER TABLE entry ADD COLUMN country character varying(36) DEFAULT NUL::character varying;
SQL;
    }
}
