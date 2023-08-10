<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308082218 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.standing_constructor (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    series_class character varying(255) DEFAULT NULL::character varying,
    standee character varying(36) NOT NULL,
    country character varying(36) DEFAULT NULL::character varying,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL
);

ALTER TABLE ONLY public.standing_constructor
    ADD CONSTRAINT pkey_standing_constructor PRIMARY KEY (id);

CREATE UNIQUE INDEX idx_standing_constructor_season_class_team_position ON public.standing_constructor USING btree (season, series_class, standee, "position");

ALTER TABLE public.standing_constructor
    ADD CONSTRAINT fk_standing_constructor_season FOREIGN KEY (season) REFERENCES season (id);
ALTER TABLE public.standing_constructor
    ADD CONSTRAINT fk_standing_constructor_standee FOREIGN KEY (standee) REFERENCES constructor (id);
ALTER TABLE public.standing_constructor
    ADD CONSTRAINT fk_standing_constructor_country FOREIGN KEY (country) REFERENCES country (id);

CREATE TABLE public.standing_driver (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    series_class character varying(255) DEFAULT NULL::character varying,
    standee character varying(36) NOT NULL,
    country character varying(36) DEFAULT NULL::character varying,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL
);

ALTER TABLE ONLY public.standing_driver
    ADD CONSTRAINT pkey_standing_driver PRIMARY KEY (id);

CREATE UNIQUE INDEX idx_standing_driver_season_class_driver_position ON public.standing_driver USING btree (season, series_class, standee, "position");

ALTER TABLE public.standing_driver
    ADD CONSTRAINT fk_standing_driver_season FOREIGN KEY (season) REFERENCES season (id);
ALTER TABLE public.standing_driver
    ADD CONSTRAINT fk_standing_driver_standee FOREIGN KEY (standee) REFERENCES driver (id);
ALTER TABLE public.standing_driver
    ADD CONSTRAINT fk_standing_driver_country FOREIGN KEY (country) REFERENCES country (id);

CREATE TABLE public.standing_team (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    series_class character varying(255) DEFAULT NULL::character varying,
    standee character varying(36) NOT NULL,
    country character varying(36) DEFAULT NULL::character varying,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL
);

ALTER TABLE ONLY public.standing_team
    ADD CONSTRAINT pkey_standing_team PRIMARY KEY (id);

CREATE UNIQUE INDEX idx_standing_team_season_class_team_position ON public.standing_team USING btree (season, series_class, standee, "position");

ALTER TABLE public.standing_team
    ADD CONSTRAINT fk_standing_team_season FOREIGN KEY (season) REFERENCES season (id);
ALTER TABLE public.standing_team
    ADD CONSTRAINT fk_standing_team_standee FOREIGN KEY (standee) REFERENCES team (id);
ALTER TABLE public.standing_team
    ADD CONSTRAINT fk_standing_team_country FOREIGN KEY (country) REFERENCES country (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.standing_constructor;
DROP TABLE public.standing_driver;
DROP TABLE public.standing_team;
SQL;
    }
}
