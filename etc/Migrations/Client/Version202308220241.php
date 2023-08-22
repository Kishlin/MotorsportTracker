<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220241 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.series (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.series
    ADD CONSTRAINT pkey_series PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.series;
SQL;
    }
}
