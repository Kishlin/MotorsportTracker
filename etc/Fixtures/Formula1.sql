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

INSERT INTO public.championships (id, name, slug) VALUES ('6f492e19-3900-4c59-91ab-06f646b46134', 'Formula 1', 'formulaone');


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.seasons (id, championship, year) VALUES ('e3730142-a4d1-441b-9760-891c1891d7b8', '6f492e19-3900-4c59-91ab-06f646b46134', 2021);


--
-- PostgreSQL database dump complete
--

