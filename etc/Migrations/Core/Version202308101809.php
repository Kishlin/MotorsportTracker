<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308101809 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.classification (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    finish_position integer NOT NULL,
    grid_position integer,
    laps integer NOT NULL,
    points double precision NOT NULL,
    lap_time double precision NOT NULL,
    classified_status character varying(255) DEFAULT NULL::character varying,
    average_lap_speed double precision NOT NULL,
    fastest_lap_time double precision,
    gap_time_to_lead double precision NOT NULL,
    gap_time_to_next double precision NOT NULL,
    gap_laps_to_lead integer NOT NULL,
    gap_laps_to_next integer NOT NULL,
    best_lap integer,
    best_time double precision,
    best_is_fastest boolean,
    best_speed double precision
);

ALTER TABLE ONLY public.classification
    ADD CONSTRAINT pkey_classification PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_classification_entry ON public.classification USING btree (entry);

ALTER TABLE public.classification
    ADD CONSTRAINT fk_classification_entry FOREIGN KEY (entry) REFERENCES entry (id);

CREATE TABLE public.retirement (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    reason character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    dns boolean NOT NULL,
    lap integer DEFAULT NULL::integer
);

ALTER TABLE ONLY public.retirement
    ADD CONSTRAINT pkey_retirement PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_retirement_entry ON public.retirement USING btree (entry);

ALTER TABLE public.retirement
    ADD CONSTRAINT fk_retirement_entry FOREIGN KEY (entry) REFERENCES entry (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.classification;
DROP TABLE public.retirement;
SQL;
    }
}
