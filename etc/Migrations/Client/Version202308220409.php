<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220409 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.race_history (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.race_history
    ADD CONSTRAINT pkey_race_history PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.race_history;
SQL;
    }
}
