<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202303222112 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.entry (
    id character varying(36) NOT NULL,
    session character varying(36) NOT NULL,
    country character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    car_number integer NOT NULL
);

ALTER TABLE ONLY public.entry
    ADD CONSTRAINT pkey_entry PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_entry_session_car_number ON public.entry USING btree (session, car_number);
CREATE UNIQUE INDEX uniq_entry_session_driver ON public.entry USING btree (session, driver);

ALTER TABLE public.entry
    ADD CONSTRAINT fk_entry_session FOREIGN KEY (session) REFERENCES event_session (id);
ALTER TABLE public.entry
    ADD CONSTRAINT fk_entry_country FOREIGN KEY (country) REFERENCES country (id);
ALTER TABLE public.entry
    ADD CONSTRAINT fk_entry_driver FOREIGN KEY (driver) REFERENCES driver (id);
ALTER TABLE public.entry
    ADD CONSTRAINT fk_entry_team FOREIGN KEY (team) REFERENCES team (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.entry;
SQL;
    }
}
