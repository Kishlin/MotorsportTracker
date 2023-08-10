<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308092010 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.analytics_drivers (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    country character varying(36) DEFAULT NULL::character varying,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL,
    avg_finish_position double precision DEFAULT 0.0 NOT NULL,
    class_wins integer DEFAULT 0 NOT NULL,
    fastest_laps integer DEFAULT 0 NOT NULL,
    final_appearances integer DEFAULT 0 NOT NULL,
    hat_tricks integer DEFAULT 0 NOT NULL,
    podiums integer DEFAULT 0 NOT NULL,
    poles integer DEFAULT 0 NOT NULL,
    races_led integer DEFAULT 0 NOT NULL,
    rallies_led integer DEFAULT 0 NOT NULL,
    retirements integer DEFAULT 0 NOT NULL,
    semi_final_appearances integer DEFAULT 0 NOT NULL,
    stage_wins integer DEFAULT 0 NOT NULL,
    starts integer DEFAULT 0 NOT NULL,
    top10s integer DEFAULT 0 NOT NULL,
    top5s integer DEFAULT 0 NOT NULL,
    wins integer DEFAULT 0 NOT NULL,
    wins_percentage double precision DEFAULT 0.0 NOT NULL
);

ALTER TABLE ONLY public.analytics_drivers
    ADD CONSTRAINT pkey_analytics_drivers PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_analytics_drivers_season_driver ON public.analytics_drivers USING btree (season, driver);

ALTER TABLE public.analytics_drivers
    ADD CONSTRAINT fk_analytics_drivers_season FOREIGN KEY (season) REFERENCES season (id);
ALTER TABLE public.analytics_drivers
    ADD CONSTRAINT fk_analytics_drivers_driver FOREIGN KEY (driver) REFERENCES driver (id);
ALTER TABLE public.analytics_drivers
    ADD CONSTRAINT fk_analytics_drivers_country FOREIGN KEY (country) REFERENCES country (id);

CREATE TABLE public.analytics_teams (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    country character varying(36) DEFAULT NULL::character varying,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL,
    class_wins integer DEFAULT 0 NOT NULL,
    fastest_laps integer DEFAULT 0 NOT NULL,
    final_appearances integer DEFAULT 0 NOT NULL,
    finishes_one_and_two integer DEFAULT 0 NOT NULL,
    podiums integer DEFAULT 0 NOT NULL,
    poles integer DEFAULT 0 NOT NULL,
    qualifies_one_and_two integer DEFAULT 0 NOT NULL,
    races_led integer DEFAULT 0 NOT NULL,
    rallies_led integer DEFAULT 0 NOT NULL,
    retirements integer DEFAULT 0 NOT NULL,
    semi_final_appearances integer DEFAULT 0 NOT NULL,
    stage_wins integer DEFAULT 0 NOT NULL,
    starts integer DEFAULT 0 NOT NULL,
    top10s integer DEFAULT 0 NOT NULL,
    top5s integer DEFAULT 0 NOT NULL,
    wins integer DEFAULT 0 NOT NULL,
    wins_percentage double precision DEFAULT 0.0 NOT NULL
);

ALTER TABLE ONLY public.analytics_teams
    ADD CONSTRAINT pkey_analytics_teams PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_analytics_teams_season_team ON public.analytics_teams USING btree (season, team);

ALTER TABLE public.analytics_teams
    ADD CONSTRAINT fk_analytics_teams_season FOREIGN KEY (season) REFERENCES season (id);
ALTER TABLE public.analytics_teams
    ADD CONSTRAINT fk_analytics_teams_driver FOREIGN KEY (team) REFERENCES team (id);
ALTER TABLE public.analytics_teams
    ADD CONSTRAINT fk_analytics_teams_country FOREIGN KEY (country) REFERENCES country (id);

CREATE TABLE public.analytics_constructors (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    constructor character varying(36) NOT NULL,
    country character varying(36) DEFAULT NULL::character varying,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL,
    wins integer DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY public.analytics_constructors
    ADD CONSTRAINT pkey_analytics_constructors PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_analytics_season_constructor ON public.analytics_constructors USING btree (season, constructor);

ALTER TABLE public.analytics_constructors
    ADD CONSTRAINT fk_analytics_constructors_season FOREIGN KEY (season) REFERENCES season (id);
ALTER TABLE public.analytics_constructors
    ADD CONSTRAINT fk_analytics_constructors_driver FOREIGN KEY (constructor) REFERENCES constructor (id);
ALTER TABLE public.analytics_constructors
    ADD CONSTRAINT fk_analytics_constructors_country FOREIGN KEY (country) REFERENCES country (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.analytics_constructors;
DROP TABLE public.analytics_drivers;
DROP TABLE public.analytics_teams;
SQL;
    }
}
