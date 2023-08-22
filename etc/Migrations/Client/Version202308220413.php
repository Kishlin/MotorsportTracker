<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220413 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.standings_drivers (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.standings_drivers
    ADD CONSTRAINT pkey_standings_drivers PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.standings_drivers;
SQL;
    }
}
