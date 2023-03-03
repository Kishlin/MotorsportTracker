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
-- Name: cars; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.cars (
    id character varying(36) NOT NULL,
    number integer NOT NULL,
    team character varying(36) NOT NULL,
    season character varying(36) NOT NULL
);


ALTER TABLE public.cars OWNER TO motorsporttracker;

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
    code character varying(255) NOT NULL
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
-- Name: driver_moves; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.driver_moves (
    id character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    car character varying(36) NOT NULL,
    date timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.driver_moves OWNER TO motorsporttracker;

--
-- Name: driver_standings; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.driver_standings (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    points double precision NOT NULL
);


ALTER TABLE public.driver_standings OWNER TO motorsporttracker;

--
-- Name: drivers; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.drivers (
    id character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    firstname character varying(255) NOT NULL,
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
    label character varying(255) NOT NULL
);


ALTER TABLE public.events OWNER TO motorsporttracker;

--
-- Name: racers; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.racers (
    id character varying(36) NOT NULL,
    car character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    startdate timestamp(0) without time zone NOT NULL,
    enddate timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.racers OWNER TO motorsporttracker;

--
-- Name: COLUMN racers.startdate; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.racers.startdate IS '(DC2Type:racer_start_date)';


--
-- Name: COLUMN racers.enddate; Type: COMMENT; Schema: public; Owner: motorsporttracker
--

COMMENT ON COLUMN public.racers.enddate IS '(DC2Type:racer_end_date)';


--
-- Name: results; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.results (
    id character varying(36) NOT NULL,
    racer character varying(36) NOT NULL,
    event_step character varying(36) NOT NULL,
    "position" character varying(255) NOT NULL,
    points double precision NOT NULL
);


ALTER TABLE public.results OWNER TO motorsporttracker;

--
-- Name: seasons; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.seasons (
    id character varying(36) NOT NULL,
    championship character varying(36) NOT NULL,
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
-- Name: team_standings; Type: TABLE; Schema: public; Owner: motorsporttracker
--

CREATE TABLE public.team_standings (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    points double precision NOT NULL
);


ALTER TABLE public.team_standings OWNER TO motorsporttracker;

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
    country character varying(36) NOT NULL
);


ALTER TABLE public.venues OWNER TO motorsporttracker;

--
-- Data for Name: cars; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.cars (id, number, team, season) FROM stdin;
\.


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

COPY public.countries (id, code) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
Kishlin\\Migrations\\Core\\Version20220408213133	2023-03-03 15:11:04	17
Kishlin\\Migrations\\Core\\Version20220409181131	2023-03-03 15:11:04	5
Kishlin\\Migrations\\Core\\Version20220409185635	2023-03-03 15:11:04	1
Kishlin\\Migrations\\Core\\Version20220610215445	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20220611002012	2023-03-03 15:11:04	1
Kishlin\\Migrations\\Core\\Version20220611170308	2023-03-03 15:11:04	3
Kishlin\\Migrations\\Core\\Version20220616000809	2023-03-03 15:11:04	9
Kishlin\\Migrations\\Core\\Version20220616124606	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20220713181313	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20220713183949	2023-03-03 15:11:04	5
Kishlin\\Migrations\\Core\\Version20220728125835	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20221023043009	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20221105182411	2023-03-03 15:11:04	6
Kishlin\\Migrations\\Core\\Version20221120040214	2023-03-03 15:11:04	0
Kishlin\\Migrations\\Core\\Version20221120065527	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20221120071036	2023-03-03 15:11:04	0
Kishlin\\Migrations\\Core\\Version20221225180107	2023-03-03 15:11:04	4
Kishlin\\Migrations\\Core\\Version20230222232032	2023-03-03 15:11:04	4
\.


--
-- Data for Name: driver_moves; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.driver_moves (id, driver, car, date) FROM stdin;
\.


--
-- Data for Name: driver_standings; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.driver_standings (id, event, driver, points) FROM stdin;
\.


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.drivers (id, name, firstname, country) FROM stdin;
\.


--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.event_steps (id, event, type, date_time) FROM stdin;
\.


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.events (id, season, venue, index, label) FROM stdin;
\.


--
-- Data for Name: racers; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.racers (id, car, driver, startdate, enddate) FROM stdin;
\.


--
-- Data for Name: results; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.results (id, racer, event_step, "position", points) FROM stdin;
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
-- Data for Name: team_standings; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.team_standings (id, event, team, points) FROM stdin;
\.


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.teams (id, name, image, country) FROM stdin;
\.


--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

COPY public.venues (id, name, country) FROM stdin;
\.


--
-- Name: cars cars_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.cars
    ADD CONSTRAINT cars_pkey PRIMARY KEY (id);


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
-- Name: driver_moves driver_moves_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_moves
    ADD CONSTRAINT driver_moves_pkey PRIMARY KEY (id);


--
-- Name: driver_standings driver_standings_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_standings
    ADD CONSTRAINT driver_standings_pkey PRIMARY KEY (id);


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
-- Name: racers racers_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.racers
    ADD CONSTRAINT racers_pkey PRIMARY KEY (id);


--
-- Name: results results_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.results
    ADD CONSTRAINT results_pkey PRIMARY KEY (id);


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
-- Name: team_standings team_standings_pkey; Type: CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_standings
    ADD CONSTRAINT team_standings_pkey PRIMARY KEY (id);


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
-- Name: car_number_season_team_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX car_number_season_team_idx ON public.cars USING btree (number, season, team);


--
-- Name: championship_created_on_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX championship_created_on_idx ON public.championship_presentations USING btree (championship, created_on);


--
-- Name: championship_season_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX championship_season_idx ON public.seasons USING btree (championship, year);


--
-- Name: driver_move_car_date_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX driver_move_car_date_idx ON public.driver_moves USING btree (car, date);


--
-- Name: driver_move_driver_date_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX driver_move_driver_date_idx ON public.driver_moves USING btree (driver, date);


--
-- Name: driver_name_firstname_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX driver_name_firstname_idx ON public.drivers USING btree (name, firstname);


--
-- Name: driver_standing_event_driver_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX driver_standing_event_driver_idx ON public.driver_standings USING btree (event, driver);


--
-- Name: event_season_label_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_season_label_idx ON public.events USING btree (season, label);


--
-- Name: event_step_event_type_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX event_step_event_type_idx ON public.event_steps USING btree (event, type);


--
-- Name: result_event_step_position_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX result_event_step_position_idx ON public.results USING btree (event_step, "position");


--
-- Name: result_event_step_racer_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX result_event_step_racer_idx ON public.results USING btree (event_step, racer);


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
-- Name: team_standing_event_team_idx; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX team_standing_event_team_idx ON public.team_standings USING btree (event, team);


--
-- Name: uniq_5d66ebad77153098; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_5d66ebad77153098 ON public.countries USING btree (code);


--
-- Name: uniq_652e22ad5e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_652e22ad5e237e06 ON public.venues USING btree (name);


--
-- Name: uniq_b682ea935e237e06; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_b682ea935e237e06 ON public.championships USING btree (name);


--
-- Name: uniq_b682ea93989d9b62; Type: INDEX; Schema: public; Owner: motorsporttracker
--

CREATE UNIQUE INDEX uniq_b682ea93989d9b62 ON public.championships USING btree (slug);


--
-- Name: cars fk_car_season; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.cars
    ADD CONSTRAINT fk_car_season FOREIGN KEY (season) REFERENCES public.seasons(id);


--
-- Name: cars fk_car_team; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.cars
    ADD CONSTRAINT fk_car_team FOREIGN KEY (team) REFERENCES public.teams(id);


--
-- Name: seasons fk_championship; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.seasons
    ADD CONSTRAINT fk_championship FOREIGN KEY (championship) REFERENCES public.championships(id);


--
-- Name: drivers fk_driver_country; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.drivers
    ADD CONSTRAINT fk_driver_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- Name: driver_moves fk_driver_moves_car; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_moves
    ADD CONSTRAINT fk_driver_moves_car FOREIGN KEY (car) REFERENCES public.cars(id);


--
-- Name: driver_moves fk_driver_moves_driver; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_moves
    ADD CONSTRAINT fk_driver_moves_driver FOREIGN KEY (driver) REFERENCES public.drivers(id);


--
-- Name: driver_standings fk_driver_standings_driver; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_standings
    ADD CONSTRAINT fk_driver_standings_driver FOREIGN KEY (driver) REFERENCES public.drivers(id);


--
-- Name: driver_standings fk_driver_standings_event; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.driver_standings
    ADD CONSTRAINT fk_driver_standings_event FOREIGN KEY (event) REFERENCES public.events(id);


--
-- Name: event_steps fk_event_steps_event; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_steps
    ADD CONSTRAINT fk_event_steps_event FOREIGN KEY (event) REFERENCES public.events(id);


--
-- Name: event_steps fk_event_steps_type; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.event_steps
    ADD CONSTRAINT fk_event_steps_type FOREIGN KEY (type) REFERENCES public.step_types(id);


--
-- Name: events fk_events_season; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT fk_events_season FOREIGN KEY (season) REFERENCES public.seasons(id);


--
-- Name: events fk_events_venue; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT fk_events_venue FOREIGN KEY (venue) REFERENCES public.venues(id);


--
-- Name: racers fk_racer_car; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.racers
    ADD CONSTRAINT fk_racer_car FOREIGN KEY (car) REFERENCES public.cars(id);


--
-- Name: racers fk_racer_driver; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.racers
    ADD CONSTRAINT fk_racer_driver FOREIGN KEY (driver) REFERENCES public.drivers(id);


--
-- Name: results fk_result_event_step; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.results
    ADD CONSTRAINT fk_result_event_step FOREIGN KEY (event_step) REFERENCES public.event_steps(id);


--
-- Name: results fk_result_racer; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.results
    ADD CONSTRAINT fk_result_racer FOREIGN KEY (racer) REFERENCES public.racers(id);


--
-- Name: teams fk_team_country; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT fk_team_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- Name: team_standings fk_team_standings_driver; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_standings
    ADD CONSTRAINT fk_team_standings_driver FOREIGN KEY (team) REFERENCES public.teams(id);


--
-- Name: team_standings fk_team_standings_event; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.team_standings
    ADD CONSTRAINT fk_team_standings_event FOREIGN KEY (event) REFERENCES public.events(id);


--
-- Name: venues fk_venue_country; Type: FK CONSTRAINT; Schema: public; Owner: motorsporttracker
--

ALTER TABLE ONLY public.venues
    ADD CONSTRAINT fk_venue_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- PostgreSQL database dump complete
--

