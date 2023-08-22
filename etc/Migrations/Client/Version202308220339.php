<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220339 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.seasons (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.seasons
    ADD CONSTRAINT pkey_seasons PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.seasons;
SQL;
    }
}
