<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220343 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.calendar (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.calendar
    ADD CONSTRAINT pkey_calendar PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.calendar;
SQL;
    }
}
