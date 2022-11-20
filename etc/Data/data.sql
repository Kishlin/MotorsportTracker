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

--
-- Data for Name: championships; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: cars; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220408213133', '2022-04-11 15:32:56', 14);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220409181131', '2022-04-11 15:32:56', 6);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220409185635', '2022-04-11 15:32:56', 1);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220610215445', '2022-06-10 22:22:19', 20);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220611002012', '2022-06-10 22:22:19', 1);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220611170308', '2022-06-11 17:04:28', 19);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220616000809', '2022-06-16 00:16:08', 28);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220616124606', '2022-06-16 12:47:03', 20);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220713181313', '2022-07-13 18:14:29', 20);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220713183949', '2022-07-13 18:40:59', 17);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20220728125835', '2022-07-28 12:59:54', 21);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20221023043009', '2022-10-23 04:33:43', 21);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20221105182411', '2022-11-05 18:26:27', 32);
INSERT INTO public.doctrine_migration_versions (version, executed_at, execution_time) VALUES ('Kishlin\Migrations\Version20221120040214', '2022-11-20 04:06:24', 13);


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: driver_moves; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: driver_standings; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: step_types; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: racers; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: results; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: team_standings; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- PostgreSQL database dump complete
--

