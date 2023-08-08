--
-- PostgreSQL database dump
--

-- Dumped from database version 14.6 (Debian 14.6-1.pgdg110+1)
-- Dumped by pg_dump version 14.6 (Debian 14.6-1.pgdg110+1)

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
-- Name: calendar_event; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.calendar_event (
    id character varying(36) NOT NULL,
    reference character varying(36),
    slug character varying(255) NOT NULL,
    index integer NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    short_code character varying(255) DEFAULT NULL::character varying,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    series text NOT NULL,
    venue json NOT NULL,
    sessions json NOT NULL
);


ALTER TABLE public.calendar_event OWNER TO motorsporttracker;

--
-- Name: COLUMN calendar_event.start_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_event.start_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN calendar_event.end_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_event.end_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN calendar_event.series; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.calendar_event.series IS '(DC2Type:calendar_event_series)';


--
-- Name: event_graph; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event_graph (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    "order" integer NOT NULL,
    type character varying(255) NOT NULL,
    data json NOT NULL
);


ALTER TABLE public.event_graph OWNER TO motorsporttracker;

--
-- Name: event_results_by_race; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event_results_by_race (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    results_by_race json NOT NULL
);


ALTER TABLE public.event_results_by_race OWNER TO motorsporttracker;

--
-- Name: migration_version; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.migration_version (
    version character varying(255) NOT NULL,
    migrated_on character varying(255) NOT NULL
);


ALTER TABLE public.migration_version OWNER TO motorsporttracker;

--
-- Name: calendar_event calendar_events_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.calendar_event
    ADD CONSTRAINT calendar_events_pkey PRIMARY KEY (id);


--
-- Name: event_graph event_graph_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_graph
    ADD CONSTRAINT event_graph_pkey PRIMARY KEY (id);


--
-- Name: event_results_by_race event_results_by_races_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_results_by_race
    ADD CONSTRAINT event_results_by_races_pkey PRIMARY KEY (id);


--
-- Name: migration_version migration_version_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.migration_version
    ADD CONSTRAINT migration_version_pkey PRIMARY KEY (version);


--
-- Name: event_graph_event_type_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_graph_event_type_idx ON public.event_graph USING btree (event, type);


--
-- Name: event_graph_id_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_graph_id_idx ON public.event_graph USING btree (id);


--
-- Name: event_results_by_race_event_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_results_by_race_event_idx ON public.event_results_by_race USING btree (event);


--
-- Name: event_results_by_race_id_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_results_by_race_id_idx ON public.event_results_by_race USING btree (id);


--
-- Name: uniq_f9e14f16989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_f9e14f16989d9b62 ON public.calendar_event USING btree (slug);


--
-- PostgreSQL database dump complete
--

