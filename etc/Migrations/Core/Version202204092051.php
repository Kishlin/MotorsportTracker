<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202204092051 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.championship (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    short_code character varying(255) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.championship
    ADD CONSTRAINT pkey_championships PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_championship_name ON public.championship USING btree (name);

CREATE UNIQUE INDEX uniq_championship_short_code ON public.championship USING btree (short_code);

CREATE UNIQUE INDEX uniq_championship_short_name ON public.championship USING btree (short_name);

CREATE UNIQUE INDEX uniq_championship_ref ON public.championship USING btree (ref);

CREATE TABLE public.season (
    id character varying(36) NOT NULL,
    championship character varying(255) NOT NULL,
    year integer NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.season
    ADD CONSTRAINT pkey_seasons PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_season_ref ON public.season USING btree (ref);

CREATE UNIQUE INDEX uniq_season_championship_year ON public.season USING btree (championship, year);

ALTER TABLE public.season
    ADD CONSTRAINT fk_season_championship FOREIGN KEY (championship)
    REFERENCES championship (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.season;
DROP TABLE public.championship;
SQL;
    }
}
