--
-- PostgreSQL database dump
--

-- Dumped from database version 13.3 (Debian 13.3-1.pgdg100+1)
-- Dumped by pg_dump version 13.3 (Debian 13.3-1.pgdg100+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: calendar_event_step_views; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.calendar_event_step_views (
    id character varying(36) NOT NULL,
    championship_slug character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    icon character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    venue_label character varying(255) NOT NULL,
    date_time timestamp(0) without time zone NOT NULL,
    reference character varying(36) NOT NULL
);


ALTER TABLE public.calendar_event_step_views OWNER TO motorsporttracker;

--
-- Name: COLUMN calendar_event_step_views.date_time; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_event_step_views.date_time IS '(DC2Type:calendar_event_step_view_date_time)';


--
-- Name: calendar_events; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.calendar_events (
    id character varying(36) NOT NULL,
    slug character varying(255) NOT NULL,
    index integer NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    series text NOT NULL,
    venue json NOT NULL,
    sessions json NOT NULL
);


ALTER TABLE public.calendar_events OWNER TO motorsporttracker;

--
-- Name: COLUMN calendar_events.start_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_events.start_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN calendar_events.end_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_events.end_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN calendar_events.series; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_events.series IS '(DC2Type:calendar_event_series)';


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO motorsporttracker;

--
-- Name: driver_standings_views; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.driver_standings_views (
    id character varying(36) NOT NULL,
    championship_slug character varying(255) NOT NULL,
    year integer NOT NULL,
    events text NOT NULL,
    standings json NOT NULL
);


ALTER TABLE public.driver_standings_views OWNER TO motorsporttracker;

--
-- Name: COLUMN driver_standings_views.events; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.driver_standings_views.events IS '(DC2Type:standings_view_events)';


--
-- Name: team_standings_views; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.team_standings_views (
    id character varying(36) NOT NULL,
    championship_slug character varying(255) NOT NULL,
    year integer NOT NULL,
    events text NOT NULL,
    standings json NOT NULL
);


ALTER TABLE public.team_standings_views OWNER TO motorsporttracker;

--
-- Name: COLUMN team_standings_views.events; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.team_standings_views.events IS '(DC2Type:standings_view_events)';


--
-- Data for Name: calendar_event_step_views; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.calendar_event_step_views (id, championship_slug, color, icon, name, type, venue_label, date_time, reference) FROM stdin;
\.


--
-- Data for Name: calendar_events; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.calendar_events (id, slug, index, name, short_name, start_date, end_date, series, venue, sessions) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
Kishlin\\Migrations\\Cache\\Version20230204042827	2023-03-03 15:05:03	14
Kishlin\\Migrations\\Cache\\Version20230218154951	2023-03-03 15:05:03	1
Kishlin\\Migrations\\Cache\\Version20230226141240	2023-03-03 15:05:03	8
Kishlin\\Migrations\\Cache\\Version20230306173718	2023-03-06 17:38:04	19
\.


--
-- Data for Name: driver_standings_views; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.driver_standings_views (id, championship_slug, year, events, standings) FROM stdin;
\.


--
-- Data for Name: team_standings_views; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.team_standings_views (id, championship_slug, year, events, standings) FROM stdin;
\.


--
-- Name: calendar_event_step_views calendar_event_step_views_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.calendar_event_step_views
    ADD CONSTRAINT calendar_event_step_views_pkey PRIMARY KEY (id);


--
-- Name: calendar_events calendar_events_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.calendar_events
    ADD CONSTRAINT calendar_events_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: driver_standings_views driver_standings_views_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_standings_views
    ADD CONSTRAINT driver_standings_views_pkey PRIMARY KEY (id);


--
-- Name: team_standings_views team_standings_views_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_standings_views
    ADD CONSTRAINT team_standings_views_pkey PRIMARY KEY (id);


--
-- Name: calendar_event_step_championship_datetime_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX calendar_event_step_championship_datetime_idx ON public.calendar_event_step_views USING btree (championship_slug, date_time);


--
-- Name: calendar_event_step_reference_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX calendar_event_step_reference_idx ON public.calendar_event_step_views USING btree (reference);


--
-- Name: driver_standings_views_championship_year_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX driver_standings_views_championship_year_idx ON public.driver_standings_views USING btree (championship_slug, year);


--
-- Name: team_standings_views_championship_year_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX team_standings_views_championship_year_idx ON public.team_standings_views USING btree (championship_slug, year);


--
-- Name: uniq_f9e14f16989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_f9e14f16989d9b62 ON public.calendar_events USING btree (slug);


--
-- PostgreSQL database dump complete
--

