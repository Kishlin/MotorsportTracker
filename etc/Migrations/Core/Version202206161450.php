<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202206161450 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.constructor (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.constructor
    ADD CONSTRAINT pkey_constructors PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_constructor_name ON public.constructor USING btree (name);
CREATE UNIQUE INDEX uniq_constructor_ref ON public.constructor USING btree (ref);

CREATE TABLE public.team (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(255) DEFAULT NULL::character varying,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.team
    ADD CONSTRAINT pkey_teams PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_team_season_name_ref ON public.team USING btree (season, name, ref);

ALTER TABLE public.team
    ADD CONSTRAINT fk_team_season FOREIGN KEY (season) REFERENCES season (id);

CREATE TABLE public.constructor_team (
    constructor character varying(36) NOT NULL,
    team character varying(36) NOT NULL
);

ALTER TABLE ONLY public.constructor_team
    ADD CONSTRAINT pkey_constructor_teams PRIMARY KEY (constructor, team);

ALTER TABLE public.constructor_team
    ADD CONSTRAINT fk_constructor_team_constructor FOREIGN KEY (constructor) REFERENCES constructor (id);

ALTER TABLE public.constructor_team
    ADD CONSTRAINT fk_constructor_team_team FOREIGN KEY (team) REFERENCES team (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.constructor_team;
DROP TABLE public.constructor;
DROP TABLE public.team;
SQL;
    }
}
