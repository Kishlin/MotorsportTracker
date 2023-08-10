<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202206110023 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.driver (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    short_code character varying(255) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.driver
    ADD CONSTRAINT pkey_drivers PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_e410c3075e237e06 ON public.driver USING btree (name);

CREATE UNIQUE INDEX uniq_e410c30f8ds6fd78 ON public.driver USING btree (ref);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.driver;
SQL;
    }
}
