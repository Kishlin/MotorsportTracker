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
-- Name: championship_presentations; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.championship_presentations (
    id character varying(36) NOT NULL,
    championship character varying(36) NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    created_on timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.championship_presentations OWNER TO motorsporttracker;

--
-- Name: COLUMN championship_presentations.created_on; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.championship_presentations.created_on IS '(DC2Type:championship_presentation_created_on)';


--
-- Name: championships; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.championships (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(36) NOT NULL
);


ALTER TABLE public.championships OWNER TO motorsporttracker;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.countries (
    id character varying(36) NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.countries OWNER TO motorsporttracker;

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
-- Name: drivers; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.drivers (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    country character varying(36) NOT NULL
);


ALTER TABLE public.drivers OWNER TO motorsporttracker;

--
-- Name: event_steps; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event_steps (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    type character varying(36) NOT NULL,
    date_time timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.event_steps OWNER TO motorsporttracker;

--
-- Name: COLUMN event_steps.date_time; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.event_steps.date_time IS '(DC2Type:event_step_date_time)';


--
-- Name: events; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.events (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    venue character varying(36) NOT NULL,
    index integer NOT NULL,
    slug character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.events OWNER TO motorsporttracker;

--
-- Name: COLUMN events.start_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.events.start_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN events.end_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.events.end_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: seasons; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.seasons (
    id character varying(36) NOT NULL,
    championship character varying(255) NOT NULL,
    year integer NOT NULL
);


ALTER TABLE public.seasons OWNER TO motorsporttracker;

--
-- Name: step_types; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.step_types (
    id character varying(36) NOT NULL,
    label character varying(255) NOT NULL
);


ALTER TABLE public.step_types OWNER TO motorsporttracker;

--
-- Name: team_presentations; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.team_presentations (
    id character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    image character varying(255) NOT NULL,
    created_on timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.team_presentations OWNER TO motorsporttracker;

--
-- Name: COLUMN team_presentations.created_on; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.team_presentations.created_on IS '(DC2Type:team_presentation_created_on)';


--
-- Name: teams; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.teams (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    image character varying(255) NOT NULL,
    country character varying(36) NOT NULL
);


ALTER TABLE public.teams OWNER TO motorsporttracker;

--
-- Name: venues; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.venues (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    country character varying(36) NOT NULL
);


ALTER TABLE public.venues OWNER TO motorsporttracker;

--
-- Data for Name: championship_presentations; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.championship_presentations (id, championship, icon, color, created_on) FROM stdin;
\.


--
-- Data for Name: championships; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.championships (id, name, slug) FROM stdin;
\.


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.countries (id, code, name) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
Kishlin\\Migrations\\Core\\Version0	2023-03-04 23:29:52	55
\.


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.drivers (id, name, country) FROM stdin;
\.


--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.event_steps (id, event, type, date_time) FROM stdin;
\.


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.events (id, season, venue, index, slug, name, short_name, start_date, end_date) FROM stdin;
\.


--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.seasons (id, championship, year) FROM stdin;
\.


--
-- Data for Name: step_types; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.step_types (id, label) FROM stdin;
\.


--
-- Data for Name: team_presentations; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.team_presentations (id, team, name, image, created_on) FROM stdin;
\.


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.teams (id, name, image, country) FROM stdin;
\.


--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.venues (id, name, slug, country) FROM stdin;
\.


--
-- Name: championship_presentations championship_presentations_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.championship_presentations
    ADD CONSTRAINT championship_presentations_pkey PRIMARY KEY (id);


--
-- Name: championships championships_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.championships
    ADD CONSTRAINT championships_pkey PRIMARY KEY (id);


--
-- Name: countries countries_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: drivers drivers_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT drivers_pkey PRIMARY KEY (id);


--
-- Name: event_steps event_steps_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_steps
    ADD CONSTRAINT event_steps_pkey PRIMARY KEY (id);


--
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: seasons seasons_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.seasons
    ADD CONSTRAINT seasons_pkey PRIMARY KEY (id);


--
-- Name: step_types step_types_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.step_types
    ADD CONSTRAINT step_types_pkey PRIMARY KEY (id);


--
-- Name: team_presentations team_presentations_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_presentations
    ADD CONSTRAINT team_presentations_pkey PRIMARY KEY (id);


--
-- Name: teams teams_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: venues venues_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.venues
    ADD CONSTRAINT venues_pkey PRIMARY KEY (id);


--
-- Name: championship_created_on_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX championship_created_on_idx ON public.championship_presentations USING btree (championship, created_on);


--
-- Name: championship_season_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX championship_season_idx ON public.seasons USING btree (championship, year);


--
-- Name: event_season_index_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_season_index_idx ON public.events USING btree (season, index);


--
-- Name: event_season_name_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_season_name_idx ON public.events USING btree (season, name);


--
-- Name: event_step_event_type_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_step_event_type_idx ON public.event_steps USING btree (event, type);


--
-- Name: step_type_label_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX step_type_label_idx ON public.step_types USING btree (label);


--
-- Name: team_name_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX team_name_idx ON public.teams USING btree (name);


--
-- Name: team_presentation_team_created_on_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX team_presentation_team_created_on_idx ON public.team_presentations USING btree (team, created_on);


--
-- Name: uniq_5387574a989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_5387574a989d9b62 ON public.events USING btree (slug);


--
-- Name: uniq_5d66ebad5e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_5d66ebad5e237e06 ON public.countries USING btree (name);


--
-- Name: uniq_5d66ebad77153098; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_5d66ebad77153098 ON public.countries USING btree (code);


--
-- Name: uniq_652e22ad5e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_652e22ad5e237e06 ON public.venues USING btree (name);


--
-- Name: uniq_652e22ad989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_652e22ad989d9b62 ON public.venues USING btree (slug);


--
-- Name: uniq_b682ea935e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_b682ea935e237e06 ON public.championships USING btree (name);


--
-- Name: uniq_b682ea93989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_b682ea93989d9b62 ON public.championships USING btree (slug);


--
-- Name: uniq_e410c3075e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_e410c3075e237e06 ON public.drivers USING btree (name);


--
-- PostgreSQL database dump complete
--

