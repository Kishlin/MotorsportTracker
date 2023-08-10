<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202206160251 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.session_type (
    id character varying(36) NOT NULL,
    label character varying(255) NOT NULL
);

ALTER TABLE ONLY public.session_type
    ADD CONSTRAINT pkey_session_types PRIMARY KEY (id);

CREATE UNIQUE INDEX idx_session_type_label ON public.session_type USING btree (label);

CREATE TABLE public.event (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    venue character varying(36) NOT NULL,
    index integer NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    status character varying(255) DEFAULT NULL::character varying,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    short_code character varying(255) DEFAULT NULL::character varying,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.event
    ADD CONSTRAINT pkey_events PRIMARY KEY (id);

CREATE UNIQUE INDEX idx_event_season_name_index ON public.event USING btree (season, name, index);
CREATE UNIQUE INDEX uniq_event_season_index ON public.event USING btree (season, index);
CREATE UNIQUE INDEX uniq_event_ref ON public.event USING btree (ref);

ALTER TABLE public.event
    ADD CONSTRAINT fk_event_season FOREIGN KEY (season) REFERENCES season (id);

ALTER TABLE public.event
    ADD CONSTRAINT fk_event_venue FOREIGN KEY (venue) REFERENCES venue (id);

CREATE TABLE public.event_session (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    type character varying(36) NOT NULL,
    has_result boolean NOT NULL,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.event_session
    ADD CONSTRAINT pkey_event_sessions PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_event_session_ref ON public.event_session USING btree (ref);

ALTER TABLE public.event_session
    ADD CONSTRAINT fk_event_session_event FOREIGN KEY (event) REFERENCES event (id);

ALTER TABLE public.event_session
    ADD CONSTRAINT fk_event_session_type FOREIGN KEY (type) REFERENCES session_type (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.event_session;
DROP TABLE public.event;

DROP TABLE public.session_type;
SQL;
    }
}
