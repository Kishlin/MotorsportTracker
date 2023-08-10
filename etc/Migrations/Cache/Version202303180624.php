<?php

declare(strict_types=1);

namespace Migrations\Cache;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202303180624 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.calendar_event (
    id character varying(36) NOT NULL,
    reference character varying(36),
    slug character varying(255) NOT NULL,
    index integer NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    short_code character varying(255) DEFAULT NULL::character varying,
    status character varying(255) DEFAULT NULL::character varying,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    series text NOT NULL,
    venue json NOT NULL,
    sessions json NOT NULL
);

ALTER TABLE ONLY public.calendar_event
    ADD CONSTRAINT pkey_calendar_events PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_calendar_event_slug ON public.calendar_event USING btree (slug);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.calendar_event;
SQL;
    }
}
