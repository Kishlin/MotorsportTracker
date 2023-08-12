<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202308121147 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE entry_additional_driver (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    driver character varying(36) NOT NULL
);

ALTER TABLE ONLY public.entry_additional_driver
    ADD CONSTRAINT pkey_entry_additional_driver PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_entry_additional_driver_driver ON public.entry_additional_driver USING btree (entry, driver);

ALTER TABLE public.entry_additional_driver
    ADD CONSTRAINT fk_entry_additional_driver_entry FOREIGN KEY (entry) REFERENCES entry (id);
ALTER TABLE public.entry_additional_driver
    ADD CONSTRAINT fk_entry_additional_driver_driver FOREIGN KEY (driver) REFERENCES driver (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE entry_additional_driver;
SQL;
    }
}
