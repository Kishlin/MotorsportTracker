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
-- Name: driver_standings_view; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.driver_standings_view (
    id character varying(36) NOT NULL,
    championship_slug character varying(255) NOT NULL,
    year integer NOT NULL,
    events text NOT NULL,
    standings json NOT NULL
);


ALTER TABLE public.driver_standings_view OWNER TO motorsporttracker;

--
-- Name: COLUMN driver_standings_view.events; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.driver_standings_view.events IS '(DC2Type:standings_view_events)';


--
-- Name: event_cached; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event_cached (
    id character varying(36) NOT NULL,
    championship character varying(255) NOT NULL,
    year integer NOT NULL,
    event character varying(255) NOT NULL
);


ALTER TABLE public.event_cached OWNER TO motorsporttracker;

--
-- Name: event_results_by_race; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event_results_by_race (
    id character varying(36) NOT NULL,
    event character varying(36),
    results_by_race json NOT NULL
);


ALTER TABLE public.event_results_by_race OWNER TO motorsporttracker;

--
-- Name: season_events; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.season_events (
    id character varying(36) NOT NULL,
    championship character varying(255) NOT NULL,
    year integer NOT NULL,
    events json NOT NULL
);


ALTER TABLE public.season_events OWNER TO motorsporttracker;

--
-- Name: team_standings_view; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.team_standings_view (
    id character varying(36) NOT NULL,
    championship_slug character varying(255) NOT NULL,
    year integer NOT NULL,
    events text NOT NULL,
    standings json NOT NULL
);


ALTER TABLE public.team_standings_view OWNER TO motorsporttracker;

--
-- Name: COLUMN team_standings_view.events; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.team_standings_view.events IS '(DC2Type:standings_view_events)';


--
-- Name: calendar_event calendar_events_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.calendar_event
    ADD CONSTRAINT calendar_events_pkey PRIMARY KEY (id);


--
-- Name: driver_standings_view driver_standings_views_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_standings_view
    ADD CONSTRAINT driver_standings_views_pkey PRIMARY KEY (id);


--
-- Name: event_cached event_cached_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_cached
    ADD CONSTRAINT event_cached_pkey PRIMARY KEY (id);


--
-- Name: event_results_by_race event_results_by_races_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_results_by_race
    ADD CONSTRAINT event_results_by_races_pkey PRIMARY KEY (id);


--
-- Name: season_events season_events_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.season_events
    ADD CONSTRAINT season_events_pkey PRIMARY KEY (id);


--
-- Name: team_standings_view team_standings_views_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_standings_view
    ADD CONSTRAINT team_standings_views_pkey PRIMARY KEY (id);


--
-- Name: driver_standings_views_championship_year_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX driver_standings_views_championship_year_idx ON public.driver_standings_view USING btree (championship_slug, year);


--
-- Name: event_cached_championship_year_event_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_cached_championship_year_event_idx ON public.event_cached USING btree (championship, year, event);


--
-- Name: event_cached_id_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_cached_id_idx ON public.event_cached USING btree (id);


--
-- Name: event_results_by_race_event_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_results_by_race_event_idx ON public.event_results_by_race USING btree (event);


--
-- Name: event_results_by_race_id_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_results_by_race_id_idx ON public.event_results_by_race USING btree (id);


--
-- Name: season_events_championship_year_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX season_events_championship_year_idx ON public.season_events USING btree (championship, year);


--
-- Name: season_events_id_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX season_events_id_idx ON public.season_events USING btree (id);


--
-- Name: team_standings_views_championship_year_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX team_standings_views_championship_year_idx ON public.team_standings_view USING btree (championship_slug, year);


--
-- Name: uniq_f9e14f16989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_f9e14f16989d9b62 ON public.calendar_event USING btree (slug);


--
-- PostgreSQL database dump complete
--

