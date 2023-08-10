<?php

declare(strict_types=1);

namespace Migrations\Core;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202301060912 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.championship_presentation (
    id character varying(36) NOT NULL,
    championship character varying(36) NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    created_on timestamp(0) without time zone NOT NULL
);

ALTER TABLE ONLY public.championship_presentation
    ADD CONSTRAINT pkey_championship_presentations PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_championship_created_on ON public.championship_presentation USING btree (championship, created_on);

ALTER TABLE public.championship_presentation
    ADD CONSTRAINT fk_championship_presentation_championship FOREIGN KEY (championship) REFERENCES championship (id);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.championship_presentation;
SQL;
    }
}
