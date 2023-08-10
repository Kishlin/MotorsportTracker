<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202206111911 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.venue (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    country character varying(36) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);

ALTER TABLE ONLY public.venue
    ADD CONSTRAINT pkey_venues PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_652e22ad5e237e06 ON public.venue USING btree (name);

CREATE UNIQUE INDEX uniq_652e22add5s9d5s9 ON public.venue USING btree (ref);

ALTER TABLE public.venue
    ADD CONSTRAINT fk_venue_country FOREIGN KEY (country)
    REFERENCES country (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.venue;
SQL;
    }
}
