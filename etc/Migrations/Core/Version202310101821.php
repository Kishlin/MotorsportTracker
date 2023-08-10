<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202310101821 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.race_lap (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    lap integer NOT NULL,
    "position" integer NOT NULL,
    pit boolean NOT NULL,
    "time" integer NOT NULL,
    laps_to_lead integer,
    time_to_lead integer,
    time_to_next integer,
    laps_to_next integer,
    tyre_details json NOT NULL
);

ALTER TABLE ONLY public.race_lap
    ADD CONSTRAINT pkey_race_lap PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_race_lap_entry_lap ON public.race_lap USING btree (entry, lap);

ALTER TABLE public.race_lap
    ADD CONSTRAINT fk_race_lap_entry FOREIGN KEY (entry) REFERENCES entry (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.race_lap;
SQL;
    }
}
