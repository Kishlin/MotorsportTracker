<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202204090001 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.country (
    id character varying(36) NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.country
    ADD CONSTRAINT pkey_countries PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_5d66ebad5e237e06 ON public.country USING btree (name);

CREATE UNIQUE INDEX uniq_5d66ebad77153098 ON public.country USING btree (code);

CREATE UNIQUE INDEX uniq_5d66ebad48484854 ON public.country USING btree (ref);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.country;
SQL;
    }
}
