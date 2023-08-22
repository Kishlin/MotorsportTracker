<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220412 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.standings_constructors (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.standings_constructors
    ADD CONSTRAINT pkey_standings_constructors PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.standings_constructors;
SQL;
    }
}
