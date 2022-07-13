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
-- Name: cars; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.cars (
    id character varying(36) NOT NULL,
    number integer NOT NULL,
    team character varying(36) NOT NULL,
    season character varying(36) NOT NULL
);


ALTER TABLE public.cars OWNER TO app;

--
-- Name: championships; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.championships (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(36) NOT NULL
);


ALTER TABLE public.championships OWNER TO app;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.countries (
    id character varying(36) NOT NULL,
    code character varying(255) NOT NULL
);


ALTER TABLE public.countries OWNER TO app;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO app;

--
-- Name: driver_moves; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.driver_moves (
    id character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    car character varying(36) NOT NULL,
    date timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.driver_moves OWNER TO app;

--
-- Name: drivers; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.drivers (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    firstname character varying(255) NOT NULL,
    country character varying(36) NOT NULL
);


ALTER TABLE public.drivers OWNER TO app;

--
-- Name: event_steps; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.event_steps (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    type character varying(36) NOT NULL,
    date_time timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.event_steps OWNER TO app;

--
-- Name: COLUMN event_steps.date_time; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.event_steps.date_time IS '(DC2Type:event_step_date_time)';


--
-- Name: events; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.events (
    id character varying(36) NOT NULL,
    season character varying(36) NOT NULL,
    venue character varying(36) NOT NULL,
    index integer NOT NULL,
    label character varying(255) NOT NULL
);


ALTER TABLE public.events OWNER TO app;

--
-- Name: seasons; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.seasons (
    id character varying(36) NOT NULL,
    championship character varying(36) NOT NULL,
    year integer NOT NULL
);


ALTER TABLE public.seasons OWNER TO app;

--
-- Name: step_types; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.step_types (
    id character varying(36) NOT NULL,
    label character varying(255) NOT NULL
);


ALTER TABLE public.step_types OWNER TO app;

--
-- Name: teams; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.teams (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    image character varying(255) NOT NULL,
    country character varying(36) NOT NULL
);


ALTER TABLE public.teams OWNER TO app;

--
-- Name: venues; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.venues (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    country character varying(36) NOT NULL
);


ALTER TABLE public.venues OWNER TO app;

--
-- Data for Name: cars; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.cars (id, number, team, season) FROM stdin;
\.


--
-- Data for Name: championships; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.championships (id, name, slug) FROM stdin;
16973325-7969-4013-a125-4844e9c72e9e	formula1	f1
\.


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.countries (id, code) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
Kishlin\\Migrations\\Version20220408213133	2022-04-11 15:32:56	14
Kishlin\\Migrations\\Version20220409181131	2022-04-11 15:32:56	6
Kishlin\\Migrations\\Version20220409185635	2022-04-11 15:32:56	1
Kishlin\\Migrations\\Version20220610215445	2022-06-10 22:22:19	20
Kishlin\\Migrations\\Version20220611002012	2022-06-10 22:22:19	1
Kishlin\\Migrations\\Version20220611170308	2022-06-11 17:04:28	19
Kishlin\\Migrations\\Version20220616000809	2022-06-16 00:16:08	28
Kishlin\\Migrations\\Version20220616124606	2022-06-16 12:47:03	20
Kishlin\\Migrations\\Version20220713181313	2022-07-13 18:14:29	20
Kishlin\\Migrations\\Version20220713183949	2022-07-13 18:40:59	17
\.


--
-- Data for Name: driver_moves; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.driver_moves (id, driver, car, date) FROM stdin;
\.


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.drivers (id, name, firstname, country) FROM stdin;
\.


--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.event_steps (id, event, type, date_time) FROM stdin;
\.


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.events (id, season, venue, index, label) FROM stdin;
\.


--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.seasons (id, championship, year) FROM stdin;
\.


--
-- Data for Name: step_types; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.step_types (id, label) FROM stdin;
\.


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.teams (id, name, image, country) FROM stdin;
\.


--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.venues (id, name, country) FROM stdin;
\.


--
-- Name: cars cars_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.cars
    ADD CONSTRAINT cars_pkey PRIMARY KEY (id);


--
-- Name: championships championships_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.championships
    ADD CONSTRAINT championships_pkey PRIMARY KEY (id);


--
-- Name: countries countries_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: driver_moves driver_moves_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.driver_moves
    ADD CONSTRAINT driver_moves_pkey PRIMARY KEY (id);


--
-- Name: drivers drivers_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT drivers_pkey PRIMARY KEY (id);


--
-- Name: event_steps event_steps_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.event_steps
    ADD CONSTRAINT event_steps_pkey PRIMARY KEY (id);


--
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: seasons seasons_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.seasons
    ADD CONSTRAINT seasons_pkey PRIMARY KEY (id);


--
-- Name: step_types step_types_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.step_types
    ADD CONSTRAINT step_types_pkey PRIMARY KEY (id);


--
-- Name: teams teams_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: venues venues_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.venues
    ADD CONSTRAINT venues_pkey PRIMARY KEY (id);


--
-- Name: car_number_season_team_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX car_number_season_team_idx ON public.cars USING btree (number, season, team);


--
-- Name: championship_season_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX championship_season_idx ON public.seasons USING btree (championship, year);


--
-- Name: driver_move_car_date_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX driver_move_car_date_idx ON public.driver_moves USING btree (car, date);


--
-- Name: driver_move_driver_date_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX driver_move_driver_date_idx ON public.driver_moves USING btree (driver, date);


--
-- Name: driver_name_firstname_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX driver_name_firstname_idx ON public.drivers USING btree (name, firstname);


--
-- Name: event_season_label_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX event_season_label_idx ON public.events USING btree (season, label);


--
-- Name: event_step_event_type_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX event_step_event_type_idx ON public.event_steps USING btree (event, type);


--
-- Name: step_type_label_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX step_type_label_idx ON public.step_types USING btree (label);


--
-- Name: team_name_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX team_name_idx ON public.teams USING btree (name);


--
-- Name: uniq_5d66ebad77153098; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_5d66ebad77153098 ON public.countries USING btree (code);


--
-- Name: uniq_652e22ad5e237e06; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_652e22ad5e237e06 ON public.venues USING btree (name);


--
-- Name: uniq_b682ea935e237e06; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_b682ea935e237e06 ON public.championships USING btree (name);


--
-- Name: uniq_b682ea93989d9b62; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_b682ea93989d9b62 ON public.championships USING btree (slug);


--
-- Name: cars fk_car_season; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.cars
    ADD CONSTRAINT fk_car_season FOREIGN KEY (season) REFERENCES public.seasons(id);


--
-- Name: cars fk_car_team; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.cars
    ADD CONSTRAINT fk_car_team FOREIGN KEY (team) REFERENCES public.teams(id);


--
-- Name: seasons fk_championship; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.seasons
    ADD CONSTRAINT fk_championship FOREIGN KEY (championship) REFERENCES public.championships(id);


--
-- Name: drivers fk_driver_country; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT fk_driver_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- Name: driver_moves fk_driver_moves_car; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.driver_moves
    ADD CONSTRAINT fk_driver_moves_car FOREIGN KEY (car) REFERENCES public.cars(id);


--
-- Name: driver_moves fk_driver_moves_driver; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.driver_moves
    ADD CONSTRAINT fk_driver_moves_driver FOREIGN KEY (driver) REFERENCES public.drivers(id);


--
-- Name: event_steps fk_event_steps_event; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.event_steps
    ADD CONSTRAINT fk_event_steps_event FOREIGN KEY (event) REFERENCES public.events(id);


--
-- Name: event_steps fk_event_steps_type; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.event_steps
    ADD CONSTRAINT fk_event_steps_type FOREIGN KEY (type) REFERENCES public.step_types(id);


--
-- Name: events fk_events_season; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT fk_events_season FOREIGN KEY (season) REFERENCES public.seasons(id);


--
-- Name: events fk_events_venue; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT fk_events_venue FOREIGN KEY (venue) REFERENCES public.venues(id);


--
-- Name: teams fk_team_country; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT fk_team_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- Name: venues fk_venue_country; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.venues
    ADD CONSTRAINT fk_venue_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- PostgreSQL database dump complete
--

