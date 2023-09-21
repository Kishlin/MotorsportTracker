<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202309212007 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
ALTER TABLE championship
    RENAME TO series;

ALTER TABLE ONLY public.series
    RENAME CONSTRAINT pkey_championships TO pkey_series;

DROP INDEX uniq_championship_name;

DROP INDEX uniq_championship_short_code;

DROP INDEX uniq_championship_short_name;

DROP INDEX uniq_championship_ref;

CREATE UNIQUE INDEX uniq_series_name ON public.series USING btree (name);

CREATE UNIQUE INDEX uniq_series_short_code ON public.series USING btree (short_code);

CREATE UNIQUE INDEX uniq_series_short_name ON public.series USING btree (short_name);

CREATE UNIQUE INDEX uniq_series_ref ON public.series USING btree (ref);

ALTER TABLE public.season
    RENAME COLUMN championship TO series;

ALTER TABLE public.season
    RENAME CONSTRAINT fk_season_championship TO fk_season_series;

DROP INDEX uniq_season_championship_year;

CREATE UNIQUE INDEX uniq_season_series_year ON public.season USING btree (series, year);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
ALTER TABLE series
    RENAME TO championship;

ALTER TABLE ONLY public.championship
    RENAME CONSTRAINT pkey_series TO pkey_championships;

DROP INDEX uniq_series_name;

DROP INDEX uniq_series_short_code;

DROP INDEX uniq_series_short_name;

DROP INDEX uniq_series_ref;

CREATE UNIQUE INDEX uniq_championship_name ON public.championship USING btree (name);

CREATE UNIQUE INDEX uniq_championship_short_code ON public.championship USING btree (short_code);

CREATE UNIQUE INDEX uniq_championship_short_name ON public.championship USING btree (short_name);

CREATE UNIQUE INDEX uniq_championship_ref ON public.championship USING btree (ref);

ALTER TABLE public.season
    RENAME COLUMN series TO championship;

ALTER TABLE public.season
    RENAME CONSTRAINT fk_season_series TO fk_season_championship;

DROP INDEX uniq_season_series_year;

CREATE UNIQUE INDEX uniq_season_championship_year ON public.season USING btree (championship, year);
SQL;
    }
}
