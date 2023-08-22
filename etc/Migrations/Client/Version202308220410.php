<?php

declare(strict_types=1);

namespace Migrations\Client;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308220410 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.classification (
    key character varying NOT NULL,
    response TEXT NOT NULL
);

ALTER TABLE ONLY public.classification
    ADD CONSTRAINT pkey_classification PRIMARY KEY (key);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.classification;
SQL;
    }
}
