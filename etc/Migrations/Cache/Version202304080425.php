<?php

declare(strict_types=1);

namespace Migrations\Cache;

use Kishlin\Backend\Persistence\Migration\Migration;

final class Version202304080425 implements Migration
{
    public function up(): string
    {
        return <<<'SQL'
CREATE TABLE public.event_graph (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    "order" integer NOT NULL,
    type character varying(255) NOT NULL,
    data json NOT NULL
);

ALTER TABLE ONLY public.event_graph
    ADD CONSTRAINT pkey_event_graph PRIMARY KEY (id);

CREATE UNIQUE INDEX uniq_event_graph_event_type ON public.event_graph USING btree (event, type);
SQL;
    }

    public function down(): string
    {
        return <<<'SQL'
DROP TABLE public.event_graph;
SQL;
    }
}
