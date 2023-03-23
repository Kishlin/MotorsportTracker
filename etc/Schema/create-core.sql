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
-- Name: analytics; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.analytics (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    "position" integer DEFAULT 0 NOT NULL,
    points double precision DEFAULT 0.0 NOT NULL,
    avg_finish_position double precision DEFAULT 0.0 NOT NULL,
    class_wins integer DEFAULT 0 NOT NULL,
    fastest_laps integer DEFAULT 0 NOT NULL,
    final_appearances integer DEFAULT 0 NOT NULL,
    hat_tricks integer DEFAULT 0 NOT NULL,
    podiums integer DEFAULT 0 NOT NULL,
    poles integer DEFAULT 0 NOT NULL,
    races_led integer DEFAULT 0 NOT NULL,
    rallies_led integer DEFAULT 0 NOT NULL,
    retirements integer DEFAULT 0 NOT NULL,
    semi_final_appearances integer DEFAULT 0 NOT NULL,
    stage_wins integer DEFAULT 0 NOT NULL,
    starts integer DEFAULT 0 NOT NULL,
    top10s integer DEFAULT 0 NOT NULL,
    top5s integer DEFAULT 0 NOT NULL,
    wins integer DEFAULT 0 NOT NULL,
    wins_percentage double precision DEFAULT 0.0 NOT NULL
);


ALTER TABLE public.analytics OWNER TO motorsporttracker;

--
-- Name: championship; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.championship (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    short_code character varying(255) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.championship OWNER TO motorsporttracker;

--
-- Name: championship_presentation; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.championship_presentation (
    id character varying(36) NOT NULL,
    championship character varying(36) NOT NULL,
    icon character varying(255) NOT NULL,
    color character varying(255) NOT NULL,
    created_on timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.championship_presentation OWNER TO motorsporttracker;

--
-- Name: COLUMN championship_presentation.created_on; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.championship_presentation.created_on IS '(DC2Type:date_time_value_object)';


--
-- Name: classification; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.classification (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    finish_position integer NOT NULL,
    grid_position integer NOT NULL,
    laps integer NOT NULL,
    points double precision NOT NULL,
    lap_time double precision NOT NULL,
    classified_status character varying(255) NOT NULL,
    average_lap_speed double precision NOT NULL,
    fastest_lap_time double precision NOT NULL,
    gap_time_to_lead double precision NOT NULL,
    gap_time_to_next double precision NOT NULL,
    gap_laps_to_lead integer NOT NULL,
    gap_laps_to_next integer NOT NULL,
    best_lap integer NOT NULL,
    best_time double precision NOT NULL,
    best_is_fastest boolean NOT NULL
);


ALTER TABLE public.classification OWNER TO motorsporttracker;

--
-- Name: country; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.country (
    id character varying(36) NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.country OWNER TO motorsporttracker;

--
-- Name: driver; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.driver (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    short_code character varying(255) NOT NULL,
    country character varying(36) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.driver OWNER TO motorsporttracker;

--
-- Name: entry; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.entry (
    id character varying(36) NOT NULL,
    session character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    car_number integer NOT NULL
);


ALTER TABLE public.entry OWNER TO motorsporttracker;

--
-- Name: event; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    venue character varying(36) NOT NULL,
    index integer NOT NULL,
    name character varying(255) NOT NULL,
    short_name character varying(255) DEFAULT NULL::character varying,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    short_code character varying(255) DEFAULT NULL::character varying,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.event OWNER TO motorsporttracker;

--
-- Name: COLUMN event.start_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.event.start_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN event.end_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.event.end_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: event_session; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.event_session (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    type character varying(36) NOT NULL,
    has_result boolean NOT NULL,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.event_session OWNER TO motorsporttracker;

--
-- Name: COLUMN event_session.start_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.event_session.start_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: COLUMN event_session.end_date; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.event_session.end_date IS '(DC2Type:nullable_date_time_value_object)';


--
-- Name: race_lap; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.race_lap (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    lap integer NOT NULL,
    "position" integer NOT NULL,
    pit boolean NOT NULL,
    "time" integer NOT NULL,
    time_to_lead integer NOT NULL,
    laps_to_lead integer NOT NULL,
    time_to_next integer NOT NULL,
    laps_to_next integer NOT NULL,
    tyre_details json NOT NULL
);


ALTER TABLE public.race_lap OWNER TO motorsporttracker;

--
-- Name: retirement; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.retirement (
    id character varying(36) NOT NULL,
    entry character varying(36) NOT NULL,
    reason character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    dns boolean NOT NULL,
    lap integer NOT NULL
);


ALTER TABLE public.retirement OWNER TO motorsporttracker;

--
-- Name: season; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.season (
    id character varying(36) NOT NULL,
    championship character varying(255) NOT NULL,
    year integer NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.season OWNER TO motorsporttracker;

--
-- Name: session_type; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.session_type (
    id character varying(36) NOT NULL,
    label character varying(255) NOT NULL
);


ALTER TABLE public.session_type OWNER TO motorsporttracker;

--
-- Name: team; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.team (
    id character varying(36) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.team OWNER TO motorsporttracker;

--
-- Name: team_presentation; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.team_presentation (
    id character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    country character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.team_presentation OWNER TO motorsporttracker;

--
-- Name: venue; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.venue (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    country character varying(36) NOT NULL,
    ref character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.venue OWNER TO motorsporttracker;

--
-- Name: analytics analytics_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.analytics
    ADD CONSTRAINT analytics_pkey PRIMARY KEY (id);


--
-- Name: championship_presentation championship_presentations_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.championship_presentation
    ADD CONSTRAINT championship_presentations_pkey PRIMARY KEY (id);


--
-- Name: championship championships_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.championship
    ADD CONSTRAINT championships_pkey PRIMARY KEY (id);


--
-- Name: classification classification_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.classification
    ADD CONSTRAINT classification_pkey PRIMARY KEY (id);


--
-- Name: country countries_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.country
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: driver drivers_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver
    ADD CONSTRAINT drivers_pkey PRIMARY KEY (id);


--
-- Name: entry entry_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.entry
    ADD CONSTRAINT entry_pkey PRIMARY KEY (id);


--
-- Name: event_session event_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_session
    ADD CONSTRAINT event_sessions_pkey PRIMARY KEY (id);


--
-- Name: event events_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: race_lap race_lap_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.race_lap
    ADD CONSTRAINT race_lap_pkey PRIMARY KEY (id);


--
-- Name: retirement retirement_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.retirement
    ADD CONSTRAINT retirement_pkey PRIMARY KEY (id);


--
-- Name: season seasons_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.season
    ADD CONSTRAINT seasons_pkey PRIMARY KEY (id);


--
-- Name: session_type session_types_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.session_type
    ADD CONSTRAINT session_types_pkey PRIMARY KEY (id);


--
-- Name: team_presentation team_presentations_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_presentation
    ADD CONSTRAINT team_presentations_pkey PRIMARY KEY (id);


--
-- Name: team teams_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: venue venues_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.venue
    ADD CONSTRAINT venues_pkey PRIMARY KEY (id);


--
-- Name: analytics_season_driver_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX analytics_season_driver_idx ON public.analytics USING btree (season, driver);


--
-- Name: championship_created_on_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX championship_created_on_idx ON public.championship_presentation USING btree (championship, created_on);


--
-- Name: championship_season_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX championship_season_idx ON public.season USING btree (championship, year);


--
-- Name: classification_entry_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX classification_entry_idx ON public.classification USING btree (entry);


--
-- Name: entry_session_car_number_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX entry_session_car_number_idx ON public.entry USING btree (session, car_number);


--
-- Name: entry_session_driver_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX entry_session_driver_idx ON public.entry USING btree (session, driver);


--
-- Name: event_season_index_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_season_index_idx ON public.event USING btree (season, index);


--
-- Name: race_lap_entry_lap_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX race_lap_entry_lap_idx ON public.race_lap USING btree (entry, lap);


--
-- Name: retirement_entry_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX retirement_entry_idx ON public.retirement USING btree (entry);


--
-- Name: session_type_label_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX session_type_label_idx ON public.session_type USING btree (label);


--
-- Name: uniq_5d66ebad5e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_5d66ebad5e237e06 ON public.country USING btree (name);


--
-- Name: uniq_5d66ebad77153098; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_5d66ebad77153098 ON public.country USING btree (code);


--
-- Name: uniq_652e22ad5e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_652e22ad5e237e06 ON public.venue USING btree (name);


--
-- Name: uniq_b682ea9317d2fe0d; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_b682ea9317d2fe0d ON public.championship USING btree (short_code);


--
-- Name: uniq_b682ea935e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_b682ea935e237e06 ON public.championship USING btree (name);


--
-- Name: uniq_e410c3075e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_e410c3075e237e06 ON public.driver USING btree (name);


--
-- PostgreSQL database dump complete
--

