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
-- Name: driver_standings; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.driver_standings (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    points double precision NOT NULL
);


ALTER TABLE public.driver_standings OWNER TO app;

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
-- Name: racers; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.racers (
    id character varying(36) NOT NULL,
    car character varying(36) NOT NULL,
    driver character varying(36) NOT NULL,
    startdate timestamp(0) without time zone NOT NULL,
    enddate timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.racers OWNER TO app;

--
-- Name: COLUMN racers.startdate; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.racers.startdate IS '(DC2Type:racer_start_date)';


--
-- Name: COLUMN racers.enddate; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.racers.enddate IS '(DC2Type:racer_end_date)';


--
-- Name: results; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.results (
    id character varying(36) NOT NULL,
    racer character varying(36) NOT NULL,
    event_step character varying(36) NOT NULL,
    "position" character varying(255) NOT NULL,
    points double precision NOT NULL
);


ALTER TABLE public.results OWNER TO app;

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
-- Name: team_standings; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.team_standings (
    id character varying(36) NOT NULL,
    event character varying(36) NOT NULL,
    team character varying(36) NOT NULL,
    points double precision NOT NULL
);


ALTER TABLE public.team_standings OWNER TO app;

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
9091a5af-b588-4632-9251-66654d35f77d	1	0521d82c-77a7-4d46-81f6-ab3741c3e554	28137908-06e4-4346-b309-c4c04dda4e10
5b927c77-32da-48ac-b6f3-c10f7e042aac	3	9d219474-9719-471e-b2f7-0ccc0f666754	28137908-06e4-4346-b309-c4c04dda4e10
ab4c09bb-e2cd-4e06-9c59-372fd8365736	4	9d219474-9719-471e-b2f7-0ccc0f666754	28137908-06e4-4346-b309-c4c04dda4e10
242e5a1a-e706-4824-905a-977767d0bb71	5	91793127-a828-4bac-870e-b781f94a8bc8	28137908-06e4-4346-b309-c4c04dda4e10
978eba5d-481b-4c9e-b44d-90f657a882f8	6	08903254-d543-482e-a358-e6129a3f4ffe	28137908-06e4-4346-b309-c4c04dda4e10
10977814-6cf1-4088-a5b8-b8e2fa70d0d4	10	287c8e13-af66-42da-8df4-2e79560afb2c	28137908-06e4-4346-b309-c4c04dda4e10
ecc7854d-5aae-42e7-a1d7-19081677f431	11	0521d82c-77a7-4d46-81f6-ab3741c3e554	28137908-06e4-4346-b309-c4c04dda4e10
5d9a78a4-7cd5-4452-8dc4-d054d6786daa	14	411baf4f-a7f5-465c-8acd-d4d649b4eaff	28137908-06e4-4346-b309-c4c04dda4e10
ada74a80-0a02-43a3-95ad-fd00dfef1683	16	664150ac-aeb0-4e06-a35c-c4a6c102cb70	28137908-06e4-4346-b309-c4c04dda4e10
782bf361-8ecc-4da3-b097-a97e0c5e4b48	18	91793127-a828-4bac-870e-b781f94a8bc8	28137908-06e4-4346-b309-c4c04dda4e10
6bd4fc55-89fd-4a61-9fa9-bbfbbdfd2de7	20	4575090f-e9e7-4a70-802b-f8cdc94f1cd3	28137908-06e4-4346-b309-c4c04dda4e10
4401843f-8fb1-40c0-8256-34086a0823d5	22	287c8e13-af66-42da-8df4-2e79560afb2c	28137908-06e4-4346-b309-c4c04dda4e10
bd096779-7175-4b01-9cd8-4d7393eb556b	23	08903254-d543-482e-a358-e6129a3f4ffe	28137908-06e4-4346-b309-c4c04dda4e10
8ed1a44c-063c-480c-8881-ff8162341f58	24	433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d	28137908-06e4-4346-b309-c4c04dda4e10
4eae9d76-8c8a-4038-ae0f-f8439962d04d	31	411baf4f-a7f5-465c-8acd-d4d649b4eaff	28137908-06e4-4346-b309-c4c04dda4e10
4b3c6596-3b68-449f-8883-492bf3792cc8	44	a050a229-f93a-49f8-9209-8ce4297f6b26	28137908-06e4-4346-b309-c4c04dda4e10
1ef5aca1-e0bc-4252-942b-d8a53ea51ca0	47	4575090f-e9e7-4a70-802b-f8cdc94f1cd3	28137908-06e4-4346-b309-c4c04dda4e10
c69289d7-1fa4-4d5d-9062-d4e8c9006359	55	664150ac-aeb0-4e06-a35c-c4a6c102cb70	28137908-06e4-4346-b309-c4c04dda4e10
b56ede6e-8211-492f-b639-762ec367e0dc	63	a050a229-f93a-49f8-9209-8ce4297f6b26	28137908-06e4-4346-b309-c4c04dda4e10
0e968bbf-c046-4116-9409-19c65ad381b3	77	433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d	28137908-06e4-4346-b309-c4c04dda4e10
0b2b5f17-6e4d-4486-8f10-2336d392dddc	27	91793127-a828-4bac-870e-b781f94a8bc8	28137908-06e4-4346-b309-c4c04dda4e10
\.


--
-- Data for Name: championships; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.championships (id, name, slug) FROM stdin;
f4618d91-2df9-4a39-b857-7b751b27111a	Formula One	formula1
\.


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.countries (id, code) FROM stdin;
db5dc596-45b7-4586-9ee3-95221f0ccb84	bh
ab04aa06-b392-4f16-8615-afdd2bf561bc	sa
b94997db-940a-4ddc-a133-a4140d168b94	au
87afcfd4-a08c-47bf-8d96-e903b34f6c39	it
73d7cf5c-6add-46cb-a97a-4757c6b7764c	us
0a7c4ba4-b3a0-4219-a5bd-6466750441a5	es
5ef1a30f-7f85-493a-8db9-265c10c435d7	mc
237e1373-7a68-4841-b541-d17f340aa003	az
7f49f09b-f1f7-4312-b5d0-e8be78e10f01	ca
849c2feb-208f-40f7-b719-131e34082381	gb
958ffab0-d0ec-4e0d-bcf5-990ec9b0f3a3	at
606e0956-7707-43b4-a065-57f59a6b2885	fr
ea893e30-9517-4613-a713-6228782141c9	hu
2801dba5-19e5-4557-acce-3829546e7639	be
b16cab89-6df3-4e96-8858-1ef550e6c946	nl
cdd3df0f-920c-4854-abfe-46dc2bbe683b	sg
29e86eef-3917-4352-afbb-9407f33ec479	jp
98d0e3cd-d803-4517-9cdb-7feb82b0422b	mx
9157eea3-8a50-4a8c-a139-8310731c0d65	br
ab2b5530-da66-453a-8833-1b1857f3e54c	ae
e0ef3345-7693-43c5-95ef-039d1de894a7	fl
e9b31ca6-40ee-4860-8cc3-55c6951f3a81	cn
2546e6e8-2d5f-4730-8f3d-7346c34991b7	th
c1400d2e-b38f-4562-a019-2f2d82f37d01	de
6f4379bb-5c80-4b10-a225-dc70d51f154c	dk
0af62fc1-f778-4488-9bc6-63ab9d31c7c7	ch
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
Kishlin\\Migrations\\Version20220728125835	2022-07-28 12:59:54	21
Kishlin\\Migrations\\Version20221023043009	2022-10-23 04:33:43	21
Kishlin\\Migrations\\Version20221105182411	2022-11-05 18:26:27	32
Kishlin\\Migrations\\Version20221120040214	2022-11-20 04:06:24	13
Kishlin\\Migrations\\Version20221120065527	2022-11-20 06:56:28	20
\.


--
-- Data for Name: driver_moves; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.driver_moves (id, driver, car, date) FROM stdin;
16743bef-907e-4d3a-95dc-6f4bc0588f2c	376f88ca-b07a-4802-b117-799421598d03	9091a5af-b588-4632-9251-66654d35f77d	2022-01-01 00:00:00
599e91ab-4680-4fc1-b232-0591ffac9b28	f9157781-1615-4d1e-9b58-6298ea1c9200	5b927c77-32da-48ac-b6f3-c10f7e042aac	2022-01-01 00:00:00
49c6078a-eefe-4616-92c2-b0862e2322cb	f4c60528-4f64-47d0-b460-15c0ec4d69fe	ab4c09bb-e2cd-4e06-9c59-372fd8365736	2022-01-01 00:00:00
3b9cd10b-bd3f-446b-8c4a-db41ae633817	b871589d-32f5-43c6-9cf6-9750b8748498	242e5a1a-e706-4824-905a-977767d0bb71	2022-01-01 00:00:00
a573977c-673a-426a-8a62-9e7c9ced9891	8345df6c-8d1a-4de8-8616-23869b2b316f	978eba5d-481b-4c9e-b44d-90f657a882f8	2022-01-01 00:00:00
b7c85850-154d-425e-acfe-d0668ee87e74	d98aaa41-6948-4216-9585-c84faedcd17c	10977814-6cf1-4088-a5b8-b8e2fa70d0d4	2022-01-01 00:00:00
831732b5-031b-4b82-a05e-32465b1d557f	59dc6702-fde8-4b2f-b04e-1a0f8ffebe38	ecc7854d-5aae-42e7-a1d7-19081677f431	2022-01-01 00:00:00
8634a1ee-9de5-4678-b2e3-cf7bc9f54579	0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96	5d9a78a4-7cd5-4452-8dc4-d054d6786daa	2022-01-01 00:00:00
19d0be81-7660-4cdf-b8e2-8b6a6584961d	886072d0-3f71-44d9-a28e-5d1120b34239	ada74a80-0a02-43a3-95ad-fd00dfef1683	2022-01-01 00:00:00
93c7790f-d413-4420-aca4-d68f0fad6bfb	b480ff53-a91e-49bb-8de2-ba673501982f	782bf361-8ecc-4da3-b097-a97e0c5e4b48	2022-01-01 00:00:00
641fd1a6-3004-4e25-be5a-1d57c4199d1e	caf2d7ff-8372-4043-b79d-e2062d799da6	6bd4fc55-89fd-4a61-9fa9-bbfbbdfd2de7	2022-01-01 00:00:00
d10136d0-d679-41da-9b2e-cf23a33e1096	4da2cb59-5bc4-4502-983c-8ab5ba156f78	4401843f-8fb1-40c0-8256-34086a0823d5	2022-01-01 00:00:00
14d11666-fb50-4893-9452-232170f1b5bc	043f15b7-7e92-4a7e-8e2d-08db6ae3c283	bd096779-7175-4b01-9cd8-4d7393eb556b	2022-01-01 00:00:00
74f611b3-aa4a-41df-98da-4e4c33a0c011	368de2ea-cc01-412e-a670-65f3512daf10	8ed1a44c-063c-480c-8881-ff8162341f58	2022-01-01 00:00:00
7fce524e-d625-4e58-87b0-c24141d0d679	b8242bfc-831e-41d3-99ca-a15906bef28b	4eae9d76-8c8a-4038-ae0f-f8439962d04d	2022-01-01 00:00:00
f66c4aad-f065-483a-b894-312f6e4b77cb	72dbaa68-4dc3-4087-90b0-8153cb441738	4b3c6596-3b68-449f-8883-492bf3792cc8	2022-01-01 00:00:00
0b8a29ef-b73a-4015-8b76-3d9ba84318b8	92bba624-1864-4053-8c7f-7f1ea6709063	1ef5aca1-e0bc-4252-942b-d8a53ea51ca0	2022-01-01 00:00:00
be112b60-4922-40e0-8e6e-f26a976ea568	5db217dc-d298-479e-ac24-0e2e0178d30a	c69289d7-1fa4-4d5d-9062-d4e8c9006359	2022-01-01 00:00:00
15677068-3205-47a3-a86a-45a08c407e98	494f6b1d-7622-4032-bf04-182a0dee404e	b56ede6e-8211-492f-b639-762ec367e0dc	2022-01-01 00:00:00
8ce73ef3-df08-4b24-b725-3972fea0d6ba	93cd5944-2646-4681-ba0c-fe24be31a2d7	0e968bbf-c046-4116-9409-19c65ad381b3	2022-01-01 00:00:00
34170647-59f6-442e-95cd-b5524bf6e031	88038686-7a0e-4d23-8043-93e56e48a804	0b2b5f17-6e4d-4486-8f10-2336d392dddc	2022-01-01 00:00:00
\.


--
-- Data for Name: driver_standings; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.driver_standings (id, event, driver, points) FROM stdin;
\.


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.drivers (id, name, firstname, country) FROM stdin;
72dbaa68-4dc3-4087-90b0-8153cb441738	Hamilton	Lewis	849c2feb-208f-40f7-b719-131e34082381
59dc6702-fde8-4b2f-b04e-1a0f8ffebe38	Perez	Sergio	98d0e3cd-d803-4517-9cdb-7feb82b0422b
376f88ca-b07a-4802-b117-799421598d03	Verstappen	Max	b16cab89-6df3-4e96-8858-1ef550e6c946
494f6b1d-7622-4032-bf04-182a0dee404e	Russell	George	849c2feb-208f-40f7-b719-131e34082381
f4c60528-4f64-47d0-b460-15c0ec4d69fe	Norris	Lando	849c2feb-208f-40f7-b719-131e34082381
f9157781-1615-4d1e-9b58-6298ea1c9200	Ricciardo	Daniel	b94997db-940a-4ddc-a133-a4140d168b94
886072d0-3f71-44d9-a28e-5d1120b34239	Leclerc	Charles	5ef1a30f-7f85-493a-8db9-265c10c435d7
5db217dc-d298-479e-ac24-0e2e0178d30a	Sainz	Carlos	0a7c4ba4-b3a0-4219-a5bd-6466750441a5
0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96	Alonso	Fernando	0a7c4ba4-b3a0-4219-a5bd-6466750441a5
b8242bfc-831e-41d3-99ca-a15906bef28b	Ocon	Esteban	606e0956-7707-43b4-a065-57f59a6b2885
d98aaa41-6948-4216-9585-c84faedcd17c	Gasly	Pierre	606e0956-7707-43b4-a065-57f59a6b2885
4da2cb59-5bc4-4502-983c-8ab5ba156f78	Tsunoda	Yuki	29e86eef-3917-4352-afbb-9407f33ec479
93cd5944-2646-4681-ba0c-fe24be31a2d7	Bottas	Valtteri	e0ef3345-7693-43c5-95ef-039d1de894a7
368de2ea-cc01-412e-a670-65f3512daf10	Guanyu	Zhou	e9b31ca6-40ee-4860-8cc3-55c6951f3a81
043f15b7-7e92-4a7e-8e2d-08db6ae3c283	Albon	Alex	2546e6e8-2d5f-4730-8f3d-7346c34991b7
8345df6c-8d1a-4de8-8616-23869b2b316f	Latifi	Nicholas	7f49f09b-f1f7-4312-b5d0-e8be78e10f01
b871589d-32f5-43c6-9cf6-9750b8748498	Vettel	Sebastian	c1400d2e-b38f-4562-a019-2f2d82f37d01
b480ff53-a91e-49bb-8de2-ba673501982f	Stroll	Lance	7f49f09b-f1f7-4312-b5d0-e8be78e10f01
92bba624-1864-4053-8c7f-7f1ea6709063	Schumacher	Mick	c1400d2e-b38f-4562-a019-2f2d82f37d01
caf2d7ff-8372-4043-b79d-e2062d799da6	Magnussen	Kevin	6f4379bb-5c80-4b10-a225-dc70d51f154c
88038686-7a0e-4d23-8043-93e56e48a804	Hulkenberg	Nico	c1400d2e-b38f-4562-a019-2f2d82f37d01
\.


--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.event_steps (id, event, type, date_time) FROM stdin;
4e1fbe33-4680-48ad-81d7-264d437bce19	51d9d255-07ec-4934-a314-d8f47302d0b7	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-03-18 12:00:00
87e39c11-e094-40e7-8ccd-487a04ed4864	51d9d255-07ec-4934-a314-d8f47302d0b7	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-03-18 15:00:00
727ce9cc-6792-4133-a3c3-bf3d3dcb2481	51d9d255-07ec-4934-a314-d8f47302d0b7	3da29b08-635d-4400-97b5-261978b92ef1	2022-03-19 12:00:00
5d9e5f7b-e9d1-49ba-950d-a702e367f718	51d9d255-07ec-4934-a314-d8f47302d0b7	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-03-19 15:00:00
335e83b4-82ec-4a83-b0ad-5e986fa70fb6	51d9d255-07ec-4934-a314-d8f47302d0b7	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-03-20 15:00:00
7408697f-df4a-43f0-824a-97df3a61f4c0	d41a2e1d-ac4e-42e9-a72d-54abd0620887	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-03-25 14:00:00
86438d95-728a-4885-b6c9-7d1ba7dffe1d	d41a2e1d-ac4e-42e9-a72d-54abd0620887	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-03-25 17:00:00
ee73fc10-a441-4617-b0c5-5f3a22dd6eb6	d41a2e1d-ac4e-42e9-a72d-54abd0620887	3da29b08-635d-4400-97b5-261978b92ef1	2022-03-26 14:00:00
15b4688c-1d11-41b3-bfa2-d1814a6eeb77	d41a2e1d-ac4e-42e9-a72d-54abd0620887	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-03-26 17:00:00
66ce89ce-6150-4db7-928e-80e7dccfdb87	d41a2e1d-ac4e-42e9-a72d-54abd0620887	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-03-27 17:00:00
0775b751-252c-48ec-be72-0f8b46b32e2f	31939543-3a9f-45f4-aa4e-78f8f2aade70	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-04-08 06:00:00
ff84931d-95b4-4d31-943f-497afa69ce23	226da250-a350-457d-864d-ebe8f47d9f73	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-06-11 14:00:00
c3f0274c-ba39-4884-a93d-186979bf134a	31939543-3a9f-45f4-aa4e-78f8f2aade70	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-04-08 03:00:00
caefc3b3-f622-44c1-beaf-9c267406f372	31939543-3a9f-45f4-aa4e-78f8f2aade70	3da29b08-635d-4400-97b5-261978b92ef1	2022-04-09 03:00:00
2b461fad-ce83-4bf6-8f3f-f74241ecaf84	31939543-3a9f-45f4-aa4e-78f8f2aade70	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-04-09 06:00:00
c6e5d69a-9dd5-40c2-8245-abe5eb311476	31939543-3a9f-45f4-aa4e-78f8f2aade70	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-04-10 05:00:00
0207dcae-1a26-449e-8207-0e6df01ed960	b7941e54-d8a0-4785-85b4-36316bff4b71	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-04-22 11:30:00
bddbb6b4-e15a-48b8-9aca-a0d028bb7201	b7941e54-d8a0-4785-85b4-36316bff4b71	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-04-22 15:00:00
be5c528d-cba2-479f-8b04-dcd210407154	b7941e54-d8a0-4785-85b4-36316bff4b71	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-04-23 10:30:00
39c033cc-8b28-4587-ad95-4ce334e5c3bc	b7941e54-d8a0-4785-85b4-36316bff4b71	2848eaca-c593-4b97-95de-6b71c114b0b5	2022-04-23 14:30:00
e9a88ef1-6672-46ca-bf46-1957e0046fb0	b7941e54-d8a0-4785-85b4-36316bff4b71	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-04-24 13:00:00
83058919-fc03-4e0b-a20b-bdd71a338378	2f11aff6-baae-4bd6-9426-678cc33281e0	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-05-06 18:30:00
c5588b1a-7318-468b-88a9-f06e2ac3bdf0	2f11aff6-baae-4bd6-9426-678cc33281e0	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-05-06 21:30:00
57456d37-541a-447e-96f7-027bb18209cc	2f11aff6-baae-4bd6-9426-678cc33281e0	3da29b08-635d-4400-97b5-261978b92ef1	2022-05-07 17:00:00
92eaaf65-61c8-4fb0-8c45-791ba43da5e1	2f11aff6-baae-4bd6-9426-678cc33281e0	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-05-07 20:00:00
74b8e5dd-838c-49d4-8dff-77b7dc940b98	2f11aff6-baae-4bd6-9426-678cc33281e0	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-05-08 19:30:00
04775cb5-7fc4-4fb6-8a87-6a9a5b0c003b	b3f67443-31cb-4263-84b3-e861ddba4e4b	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-05-20 15:00:00
ee6b750a-83eb-4d12-909e-b1deb292111e	b3f67443-31cb-4263-84b3-e861ddba4e4b	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-05-20 12:00:00
ed57ee09-030b-483b-92d9-d63a43b7b9f8	b3f67443-31cb-4263-84b3-e861ddba4e4b	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-05-22 13:00:00
61f52072-6048-4500-89a9-e05bab0f5b78	b3f67443-31cb-4263-84b3-e861ddba4e4b	3da29b08-635d-4400-97b5-261978b92ef1	2022-05-21 11:00:00
65200673-c8a9-4100-aea8-3d8ec3e07d0d	b3f67443-31cb-4263-84b3-e861ddba4e4b	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-05-21 14:00:00
491f76bd-9487-49a5-a934-98536c4da061	6afbb5e2-e169-411f-9f30-e1919f4fbb6a	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-05-27 12:00:00
2905ef05-c466-43f9-9f0a-c455d47b1ac3	6afbb5e2-e169-411f-9f30-e1919f4fbb6a	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-05-27 15:00:00
bc346ba8-6f3d-4cf6-80a5-67a1700c2b90	6afbb5e2-e169-411f-9f30-e1919f4fbb6a	3da29b08-635d-4400-97b5-261978b92ef1	2022-05-28 11:00:00
381d713b-9c03-4a67-8886-ff5dc1947d27	6afbb5e2-e169-411f-9f30-e1919f4fbb6a	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-05-28 14:00:00
b3e9ef4c-9a22-4525-930d-a56cdecad111	6afbb5e2-e169-411f-9f30-e1919f4fbb6a	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-05-29 13:00:00
a04185d0-6a3c-475d-bfa1-3ec50e279396	226da250-a350-457d-864d-ebe8f47d9f73	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-06-10 11:00:00
5b8afd7b-e533-43a3-9f7e-7fe58ac29d11	226da250-a350-457d-864d-ebe8f47d9f73	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-06-10 14:00:00
442c53cd-4109-42da-a20f-a50b72fe682d	226da250-a350-457d-864d-ebe8f47d9f73	3da29b08-635d-4400-97b5-261978b92ef1	2022-06-11 11:00:00
a8ebaa61-053b-4c39-b9bd-be494c5aa82a	226da250-a350-457d-864d-ebe8f47d9f73	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-06-12 11:00:00
8496286c-bc26-4d7a-ba4e-2b89731c7eba	967cb75d-60f6-4998-9e60-ba45e94c3ee8	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-06-17 18:00:00
2530f627-e816-42c1-9255-bb5a23f4b923	967cb75d-60f6-4998-9e60-ba45e94c3ee8	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-06-17 21:00:00
cf39e5ab-4d85-4d18-9d0c-e9aeb46d1095	967cb75d-60f6-4998-9e60-ba45e94c3ee8	3da29b08-635d-4400-97b5-261978b92ef1	2022-06-18 17:00:00
11f50c3b-2c72-4174-af53-8eef221e0ff4	967cb75d-60f6-4998-9e60-ba45e94c3ee8	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-06-18 20:00:00
99d4c260-715b-4af2-b82b-1a6beac17a6d	967cb75d-60f6-4998-9e60-ba45e94c3ee8	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-06-19 18:00:00
c91bf7ae-670a-4248-a571-61cd3a154800	5565ab3f-ee96-474f-a266-b1a83885aaa9	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-07-01 12:00:00
c3aabafa-e984-4d37-8618-11dcf2e4a558	5565ab3f-ee96-474f-a266-b1a83885aaa9	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-07-01 15:00:00
7bc6c9da-6f55-4412-b947-a03279542225	5565ab3f-ee96-474f-a266-b1a83885aaa9	3da29b08-635d-4400-97b5-261978b92ef1	2022-07-02 11:00:00
3a6228dd-9a7b-420d-928d-d8c4b6ee62a0	5565ab3f-ee96-474f-a266-b1a83885aaa9	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-07-02 14:00:00
2fc09c78-4025-4e95-b8f0-84aaa413521c	5565ab3f-ee96-474f-a266-b1a83885aaa9	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-07-03 14:00:00
0d7a764d-e6d1-4d0f-87c7-cc6b8aaa9e63	f5aabd63-10dc-4bf7-908f-b78b48cfe866	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-07-08 15:00:00
02524657-b6a6-44d1-afb6-9349574615b5	f5aabd63-10dc-4bf7-908f-b78b48cfe866	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-07-09 10:30:00
6c65d484-4acb-4cbb-95f0-6ba00187c6af	f5aabd63-10dc-4bf7-908f-b78b48cfe866	2848eaca-c593-4b97-95de-6b71c114b0b5	2022-07-09 14:30:00
50247b27-8dec-4684-be79-ce39fa564109	f5aabd63-10dc-4bf7-908f-b78b48cfe866	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-07-10 13:00:00
90837dd0-f9e5-4db3-8c9e-83a280916f5a	f5aabd63-10dc-4bf7-908f-b78b48cfe866	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-07-08 11:30:00
12ffe553-d809-411d-946c-4f1b87135c6e	fcbe8a95-ab56-4ec6-815b-e12e3d3eda54	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-07-22 12:00:00
4b28bf74-78e2-4947-ab5b-e087ad85f45e	fcbe8a95-ab56-4ec6-815b-e12e3d3eda54	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-07-22 15:00:00
bf1251d3-f571-478e-8239-71631690a945	fcbe8a95-ab56-4ec6-815b-e12e3d3eda54	3da29b08-635d-4400-97b5-261978b92ef1	2022-07-23 11:00:00
02b4cc72-9de0-4134-a04d-40a5d4503337	fcbe8a95-ab56-4ec6-815b-e12e3d3eda54	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-07-23 14:00:00
89e06d9b-abd8-423e-aac7-f4e96fe07739	fcbe8a95-ab56-4ec6-815b-e12e3d3eda54	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-07-24 13:00:00
23cdf4be-5631-4cc2-80ab-29fd33252acb	20f33cc2-edf0-462d-813f-3c88853c5935	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-07-29 12:00:00
d6383008-429d-4992-a0d8-177bd9fbcc25	20f33cc2-edf0-462d-813f-3c88853c5935	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-07-29 15:00:00
98d581ba-7285-43c9-a039-2000a675b996	20f33cc2-edf0-462d-813f-3c88853c5935	3da29b08-635d-4400-97b5-261978b92ef1	2022-07-30 11:00:00
c4437881-ff19-4acf-9d70-f3746dd7a944	20f33cc2-edf0-462d-813f-3c88853c5935	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-07-30 14:00:00
7786220c-ffdd-41a1-b86c-b8f289028cc0	20f33cc2-edf0-462d-813f-3c88853c5935	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-07-31 13:00:00
b1249f78-99dc-45e7-8902-42c88648e3af	9edfaf1e-6fe2-44ba-92fb-0b946fc64f59	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-08-26 12:00:00
3a46a851-e07e-47ce-8377-a7c04d85d416	9edfaf1e-6fe2-44ba-92fb-0b946fc64f59	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-08-26 15:00:00
8a28823c-a440-496b-a07a-8d1c0f1f3eb4	9edfaf1e-6fe2-44ba-92fb-0b946fc64f59	3da29b08-635d-4400-97b5-261978b92ef1	2022-08-27 11:00:00
df50d9cc-92ab-46a5-9406-692675c7ed45	9edfaf1e-6fe2-44ba-92fb-0b946fc64f59	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-08-27 14:00:00
d198694d-26d2-4d83-8df6-aa3ed81c478f	9edfaf1e-6fe2-44ba-92fb-0b946fc64f59	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-08-28 13:00:00
f343420c-55b4-4694-a78a-baecd0a7e91a	1c6c16fb-e472-48d3-8bc6-7000a342f881	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-09-02 10:30:00
ce1b4779-050f-4b08-8c2d-59295be8f2cd	1c6c16fb-e472-48d3-8bc6-7000a342f881	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-09-02 14:00:00
aca50e76-af81-4787-aa3c-176282c8bcbf	1c6c16fb-e472-48d3-8bc6-7000a342f881	3da29b08-635d-4400-97b5-261978b92ef1	2022-09-03 10:00:00
1532135e-00d2-4d62-94c4-8f473ff1e9c2	1c6c16fb-e472-48d3-8bc6-7000a342f881	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-09-03 13:00:00
b0cae2ba-cb8b-4a89-8973-93b8cbafe728	1c6c16fb-e472-48d3-8bc6-7000a342f881	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-09-04 13:00:00
1fbb07bb-29ad-4dee-82df-1142ab693df4	dd2bc70a-71d8-48c2-b36e-01a765d7a5e9	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-09-09 12:00:00
374ae7d0-12ff-4525-bf32-d14cd603da6b	dd2bc70a-71d8-48c2-b36e-01a765d7a5e9	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-09-09 15:00:00
335e2158-073e-4243-a33e-6f109adb7ff7	dd2bc70a-71d8-48c2-b36e-01a765d7a5e9	3da29b08-635d-4400-97b5-261978b92ef1	2022-09-10 11:00:00
e06f7484-7287-4cd6-a6b5-0b8946f87e8f	dd2bc70a-71d8-48c2-b36e-01a765d7a5e9	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-09-10 14:00:00
8ca32f26-fe7a-4bad-941f-b403ea958642	dd2bc70a-71d8-48c2-b36e-01a765d7a5e9	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-09-11 13:00:00
655b9560-05d2-4e39-ab33-c343082c43ca	1ca163e0-f93a-41e0-b935-ebd1ea86bf97	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-09-30 10:00:00
cf2a0778-853a-4fdb-b7e6-0fd8e2dd8079	1ca163e0-f93a-41e0-b935-ebd1ea86bf97	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-09-30 13:00:00
103fce01-dbd5-4cc1-b847-e61bf1c7fa2e	1ca163e0-f93a-41e0-b935-ebd1ea86bf97	3da29b08-635d-4400-97b5-261978b92ef1	2022-10-01 10:00:00
906f9587-2270-4a2d-a832-39720aaea23a	1ca163e0-f93a-41e0-b935-ebd1ea86bf97	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-10-01 13:00:00
84636143-56dd-45dc-80bd-eb4f08cd2694	1ca163e0-f93a-41e0-b935-ebd1ea86bf97	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-10-02 12:00:00
677b26b3-5490-47c5-8ac3-4ec94ccf0a15	44d55619-b5ca-4d9d-b33d-293cd87272af	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-10-07 03:00:00
9f96c34a-d8e4-43ed-9e90-fa484c61362e	44d55619-b5ca-4d9d-b33d-293cd87272af	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-10-07 06:00:00
9f3f5572-6054-4609-a0d4-3da086d39add	44d55619-b5ca-4d9d-b33d-293cd87272af	3da29b08-635d-4400-97b5-261978b92ef1	2022-10-08 03:00:00
c22ef212-bc2a-47c5-a24c-7c656c346297	44d55619-b5ca-4d9d-b33d-293cd87272af	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-10-08 06:00:00
94df2b80-7a55-42e1-aa42-f8cf8b6c7cb7	44d55619-b5ca-4d9d-b33d-293cd87272af	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-10-09 05:00:00
b32a150f-3be6-4cf3-9f6f-d5c634925830	c83f5cc3-47ed-46b6-baeb-3bfe254b5647	3da29b08-635d-4400-97b5-261978b92ef1	2022-10-22 19:00:00
81c0dcc5-0c66-4bbe-96c4-21e379a07935	c83f5cc3-47ed-46b6-baeb-3bfe254b5647	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-10-22 22:00:00
143b8039-53d9-443e-9fe7-0821253bd841	c83f5cc3-47ed-46b6-baeb-3bfe254b5647	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-10-23 19:00:00
86696623-af33-41b1-8640-cce56af165de	c83f5cc3-47ed-46b6-baeb-3bfe254b5647	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-10-21 19:00:00
295e284e-bdb3-4802-bd5c-3845686ad1d5	c83f5cc3-47ed-46b6-baeb-3bfe254b5647	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-10-21 22:00:00
a2152f47-0665-4922-9d1d-1dd6ddffeba1	384ca8e9-9563-4908-b487-024f0653a0cc	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-10-28 18:00:00
e124a4cb-f9a3-4f46-a3ff-15cd537447a7	384ca8e9-9563-4908-b487-024f0653a0cc	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-10-28 21:00:00
1aaac26f-5622-4b95-a8ca-f3c50e888748	384ca8e9-9563-4908-b487-024f0653a0cc	3da29b08-635d-4400-97b5-261978b92ef1	2022-10-29 17:00:00
36037f01-d98c-476e-97b4-c78518c7122a	384ca8e9-9563-4908-b487-024f0653a0cc	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-10-29 20:00:00
fdc57e37-65e8-4475-a354-6fc83db52de7	384ca8e9-9563-4908-b487-024f0653a0cc	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-10-30 20:00:00
e8ad2439-7e33-4d6d-935c-2333683b6550	9165f124-a621-476f-a419-d1fc4727ac27	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-11-11 15:30:00
904302d8-94b7-4a0b-905c-00fefc028e29	9165f124-a621-476f-a419-d1fc4727ac27	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-11-11 19:00:00
6acaf51c-cbff-49ba-8391-1b8429e881c8	9165f124-a621-476f-a419-d1fc4727ac27	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-11-12 15:30:00
cd34909c-cd6f-4c08-9075-9abfb3253931	9165f124-a621-476f-a419-d1fc4727ac27	2848eaca-c593-4b97-95de-6b71c114b0b5	2022-11-12 19:30:00
423b3b12-f4b5-47d5-ab10-178e478421c4	9165f124-a621-476f-a419-d1fc4727ac27	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-11-13 18:00:00
bedaccfa-0a35-4564-8770-7c63bd427c89	debaeb4c-92e5-4309-9004-e85727ff7b12	a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	2022-11-18 10:00:00
61f05848-a868-4353-af4f-152859914a8e	debaeb4c-92e5-4309-9004-e85727ff7b12	2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	2022-11-18 13:00:00
2f999c42-e3f7-4f51-b6b1-83e2d850181f	debaeb4c-92e5-4309-9004-e85727ff7b12	3da29b08-635d-4400-97b5-261978b92ef1	2022-11-19 10:30:00
acdc2598-3b6a-4ae9-aacd-6e185fe3ae8f	debaeb4c-92e5-4309-9004-e85727ff7b12	3c56e36c-14f8-4c69-8976-c22105359e5a	2022-11-19 13:00:00
97db111b-44f7-4093-9ed3-267606ec704f	debaeb4c-92e5-4309-9004-e85727ff7b12	b3418e4c-73dc-4304-b45a-c64bc9fb3f27	2022-11-20 12:00:00
\.


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.events (id, season, venue, index, label) FROM stdin;
b7941e54-d8a0-4785-85b4-36316bff4b71	28137908-06e4-4346-b309-c4c04dda4e10	ef313471-20cc-42c3-91eb-9d079a7f7b03	3	Rolex Emilia Romagna GP
6afbb5e2-e169-411f-9f30-e1919f4fbb6a	28137908-06e4-4346-b309-c4c04dda4e10	7242a078-f76c-4166-8658-70eb8fc909a0	6	Monaco GP
9165f124-a621-476f-a419-d1fc4727ac27	28137908-06e4-4346-b309-c4c04dda4e10	9cc0ccc4-504c-423d-a16e-24c951d36a4b	20	Heineken Brazil GP
51d9d255-07ec-4934-a314-d8f47302d0b7	28137908-06e4-4346-b309-c4c04dda4e10	409bb6eb-aaaf-48b9-9012-07bc9628ffe2	0	Gulf Air Bahrain Grand Prix
967cb75d-60f6-4998-9e60-ba45e94c3ee8	28137908-06e4-4346-b309-c4c04dda4e10	89c1ba61-c777-461e-9abc-c524c34a3b68	8	AWS Canada GP
2f11aff6-baae-4bd6-9426-678cc33281e0	28137908-06e4-4346-b309-c4c04dda4e10	c2116751-22ca-40d1-bf2f-2fd30ab0cd18	4	Crypto.com Miami GP
b3f67443-31cb-4263-84b3-e861ddba4e4b	28137908-06e4-4346-b309-c4c04dda4e10	d2f04839-e8de-484a-98f7-beda803d4239	5	Pirelli Spanish GP
9edfaf1e-6fe2-44ba-92fb-0b946fc64f59	28137908-06e4-4346-b309-c4c04dda4e10	c85b066e-72fe-41e9-b787-3cb4c6ca5de9	13	Rolex Belgian GP
dd2bc70a-71d8-48c2-b36e-01a765d7a5e9	28137908-06e4-4346-b309-c4c04dda4e10	9d5146df-6054-4238-bb63-897d1f4dcc2b	15	Pirelli Italian GP
5565ab3f-ee96-474f-a266-b1a83885aaa9	28137908-06e4-4346-b309-c4c04dda4e10	0965e59b-fe6f-4799-8239-01f95d665ef4	9	Lenovo British GP
20f33cc2-edf0-462d-813f-3c88853c5935	28137908-06e4-4346-b309-c4c04dda4e10	90cf17ad-29f6-43c1-999a-84674b0c758f	12	Aramco Hungarian GP
1c6c16fb-e472-48d3-8bc6-7000a342f881	28137908-06e4-4346-b309-c4c04dda4e10	6d6cf63e-751d-4153-bff4-354ab70951fb	14	Heineken Dutch GP
f5aabd63-10dc-4bf7-908f-b78b48cfe866	28137908-06e4-4346-b309-c4c04dda4e10	0455078c-93a1-41c1-ac3a-6149908b04fa	10	Rolex Austrian GP
fcbe8a95-ab56-4ec6-815b-e12e3d3eda54	28137908-06e4-4346-b309-c4c04dda4e10	05c6b668-ef04-4412-9a1f-46cf8c1aabbf	11	Lenovo French GP
1ca163e0-f93a-41e0-b935-ebd1ea86bf97	28137908-06e4-4346-b309-c4c04dda4e10	bd9bf718-17d4-4f9b-b3ee-5f95b6155611	16	Singapore Air Singapore GP
c83f5cc3-47ed-46b6-baeb-3bfe254b5647	28137908-06e4-4346-b309-c4c04dda4e10	71ed29b0-1ec8-4402-8d85-65e1ed6d7eef	18	Aramco United States GP
d41a2e1d-ac4e-42e9-a72d-54abd0620887	28137908-06e4-4346-b309-c4c04dda4e10	e400d424-2bee-47ab-a1f3-637823129f88	1	STC Saudi Arabian GP
debaeb4c-92e5-4309-9004-e85727ff7b12	28137908-06e4-4346-b309-c4c04dda4e10	508affbb-0506-4423-9fe3-725b49d95274	21	Etihad Airways Abu Dhabi GP
31939543-3a9f-45f4-aa4e-78f8f2aade70	28137908-06e4-4346-b309-c4c04dda4e10	a9cbe6b1-e38f-417c-b206-824fc7ae1f7c	2	Heineken Australian GP
44d55619-b5ca-4d9d-b33d-293cd87272af	28137908-06e4-4346-b309-c4c04dda4e10	bf8dc1d2-760f-468f-93ad-046230923184	17	Honda Japanese GP
384ca8e9-9563-4908-b487-024f0653a0cc	28137908-06e4-4346-b309-c4c04dda4e10	415d0c44-0804-445c-9579-56fe5e976dd5	19	Mexico GP
226da250-a350-457d-864d-ebe8f47d9f73	28137908-06e4-4346-b309-c4c04dda4e10	13d8a8ce-074f-487c-b514-fd109f62934b	7	Azerbaijan GP
\.


--
-- Data for Name: racers; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.racers (id, car, driver, startdate, enddate) FROM stdin;
6faa4747-db3c-4869-8532-d4706ee921be	9091a5af-b588-4632-9251-66654d35f77d	376f88ca-b07a-4802-b117-799421598d03	2022-01-01 00:00:00	2022-12-31 23:59:59
f24a8df4-e2d5-4b11-a0ba-2c9eba96abb4	5b927c77-32da-48ac-b6f3-c10f7e042aac	f9157781-1615-4d1e-9b58-6298ea1c9200	2022-01-01 00:00:00	2022-12-31 23:59:59
22e3a5e7-b814-4912-bd23-9b5abb67af4c	ab4c09bb-e2cd-4e06-9c59-372fd8365736	f4c60528-4f64-47d0-b460-15c0ec4d69fe	2022-01-01 00:00:00	2022-12-31 23:59:59
27426e68-0d4f-428b-85c9-c917cc808ac6	978eba5d-481b-4c9e-b44d-90f657a882f8	8345df6c-8d1a-4de8-8616-23869b2b316f	2022-01-01 00:00:00	2022-12-31 23:59:59
eac10244-6119-46a1-9541-7a486ec83442	10977814-6cf1-4088-a5b8-b8e2fa70d0d4	d98aaa41-6948-4216-9585-c84faedcd17c	2022-01-01 00:00:00	2022-12-31 23:59:59
cc56f492-ae21-47d5-92db-f0e94fb059ff	ecc7854d-5aae-42e7-a1d7-19081677f431	59dc6702-fde8-4b2f-b04e-1a0f8ffebe38	2022-01-01 00:00:00	2022-12-31 23:59:59
ef9ed0e7-568f-40e3-af6e-1d48ff961a65	5d9a78a4-7cd5-4452-8dc4-d054d6786daa	0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96	2022-01-01 00:00:00	2022-12-31 23:59:59
2abfcd71-9956-4ce3-a0a3-2057c811bf78	ada74a80-0a02-43a3-95ad-fd00dfef1683	886072d0-3f71-44d9-a28e-5d1120b34239	2022-01-01 00:00:00	2022-12-31 23:59:59
1006b479-80e7-43fd-a65c-73e7724c803c	782bf361-8ecc-4da3-b097-a97e0c5e4b48	b480ff53-a91e-49bb-8de2-ba673501982f	2022-01-01 00:00:00	2022-12-31 23:59:59
c2ae3859-8655-4ae0-8001-ccc3b627f75c	6bd4fc55-89fd-4a61-9fa9-bbfbbdfd2de7	caf2d7ff-8372-4043-b79d-e2062d799da6	2022-01-01 00:00:00	2022-12-31 23:59:59
514df511-4b82-4b1e-8c0a-1ca7f810ff8f	4401843f-8fb1-40c0-8256-34086a0823d5	4da2cb59-5bc4-4502-983c-8ab5ba156f78	2022-01-01 00:00:00	2022-12-31 23:59:59
94b15ca0-6d88-4782-a51d-9d2674f467d4	bd096779-7175-4b01-9cd8-4d7393eb556b	043f15b7-7e92-4a7e-8e2d-08db6ae3c283	2022-01-01 00:00:00	2022-12-31 23:59:59
423422d0-9af2-49d2-9b71-82f5b90ddc42	8ed1a44c-063c-480c-8881-ff8162341f58	368de2ea-cc01-412e-a670-65f3512daf10	2022-01-01 00:00:00	2022-12-31 23:59:59
67439855-dfa0-4922-9a01-71cf88c00baa	4eae9d76-8c8a-4038-ae0f-f8439962d04d	b8242bfc-831e-41d3-99ca-a15906bef28b	2022-01-01 00:00:00	2022-12-31 23:59:59
377dc90d-0a50-4826-aaa4-062bb6e23afb	4b3c6596-3b68-449f-8883-492bf3792cc8	72dbaa68-4dc3-4087-90b0-8153cb441738	2022-01-01 00:00:00	2022-12-31 23:59:59
31e342a0-5535-4196-8adc-55ee504c5522	1ef5aca1-e0bc-4252-942b-d8a53ea51ca0	92bba624-1864-4053-8c7f-7f1ea6709063	2022-01-01 00:00:00	2022-12-31 23:59:59
8928f1bd-57d4-4711-bca4-e242078a92fe	c69289d7-1fa4-4d5d-9062-d4e8c9006359	5db217dc-d298-479e-ac24-0e2e0178d30a	2022-01-01 00:00:00	2022-12-31 23:59:59
0d4d5a06-717a-4447-9e35-5814f3e21047	b56ede6e-8211-492f-b639-762ec367e0dc	494f6b1d-7622-4032-bf04-182a0dee404e	2022-01-01 00:00:00	2022-12-31 23:59:59
1ca83a76-9c12-4609-9508-24b8d7b7fa7a	0e968bbf-c046-4116-9409-19c65ad381b3	93cd5944-2646-4681-ba0c-fe24be31a2d7	2022-01-01 00:00:00	2022-12-31 23:59:59
cda5215b-aed3-4528-ad43-f751060dcbba	0b2b5f17-6e4d-4486-8f10-2336d392dddc	88038686-7a0e-4d23-8043-93e56e48a804	2022-01-01 00:00:00	2022-03-25 23:59:59
3a96b26d-f495-479a-8ac2-584058e09e26	242e5a1a-e706-4824-905a-977767d0bb71	b871589d-32f5-43c6-9cf6-9750b8748498	2022-03-26 00:00:00	2022-12-31 23:59:59
\.


--
-- Data for Name: results; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.results (id, racer, event_step, "position", points) FROM stdin;
\.


--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.seasons (id, championship, year) FROM stdin;
28137908-06e4-4346-b309-c4c04dda4e10	f4618d91-2df9-4a39-b857-7b751b27111a	2022
\.


--
-- Data for Name: step_types; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.step_types (id, label) FROM stdin;
a10f354f-b35d-4e0a-88cf-e1a6ebe82d75	Practice 1
2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06	Practice 2
3da29b08-635d-4400-97b5-261978b92ef1	Practice 3
3c56e36c-14f8-4c69-8976-c22105359e5a	Qualifying
b3418e4c-73dc-4304-b45a-c64bc9fb3f27	Race
2848eaca-c593-4b97-95de-6b71c114b0b5	Sprint Qualifying
\.


--
-- Data for Name: team_standings; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.team_standings (id, event, team, points) FROM stdin;
\.


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.teams (id, name, image, country) FROM stdin;
a050a229-f93a-49f8-9209-8ce4297f6b26	Mercedes	/mercedes.png	c1400d2e-b38f-4562-a019-2f2d82f37d01
0521d82c-77a7-4d46-81f6-ab3741c3e554	Red Bull Racing	/redbullracing.png	958ffab0-d0ec-4e0d-bcf5-990ec9b0f3a3
664150ac-aeb0-4e06-a35c-c4a6c102cb70	Ferrari	/ferrari.png	87afcfd4-a08c-47bf-8d96-e903b34f6c39
411baf4f-a7f5-465c-8acd-d4d649b4eaff	Alpine	/alpine.png	606e0956-7707-43b4-a065-57f59a6b2885
9d219474-9719-471e-b2f7-0ccc0f666754	McLaren	/mclaren.png	849c2feb-208f-40f7-b719-131e34082381
08903254-d543-482e-a358-e6129a3f4ffe	Williams	/williams.png	849c2feb-208f-40f7-b719-131e34082381
91793127-a828-4bac-870e-b781f94a8bc8	Aston Martin	/astonmartin.png	849c2feb-208f-40f7-b719-131e34082381
4575090f-e9e7-4a70-802b-f8cdc94f1cd3	Haas	/haas.png	73d7cf5c-6add-46cb-a97a-4757c6b7764c
287c8e13-af66-42da-8df4-2e79560afb2c	Alpha Tauri	/alphatauri.png	87afcfd4-a08c-47bf-8d96-e903b34f6c39
433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d	Alfa Romeo Racing	/alfaromeoracing.png	0af62fc1-f778-4488-9bc6-63ab9d31c7c7
\.


--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.venues (id, name, country) FROM stdin;
409bb6eb-aaaf-48b9-9012-07bc9628ffe2	Bahrain International Circuit	db5dc596-45b7-4586-9ee3-95221f0ccb84
e400d424-2bee-47ab-a1f3-637823129f88	Jeddah Street Circuit	ab04aa06-b392-4f16-8615-afdd2bf561bc
a9cbe6b1-e38f-417c-b206-824fc7ae1f7c	Melbourne Grand Prix Circuit	b94997db-940a-4ddc-a133-a4140d168b94
ef313471-20cc-42c3-91eb-9d079a7f7b03	Autodromo Internazionale Enzo e Dino Ferrari	87afcfd4-a08c-47bf-8d96-e903b34f6c39
c2116751-22ca-40d1-bf2f-2fd30ab0cd18	Miami International Autodrome	73d7cf5c-6add-46cb-a97a-4757c6b7764c
d2f04839-e8de-484a-98f7-beda803d4239	Circuit de Barcelona-Catalunya	0a7c4ba4-b3a0-4219-a5bd-6466750441a5
7242a078-f76c-4166-8658-70eb8fc909a0	Circuit de Monaco	5ef1a30f-7f85-493a-8db9-265c10c435d7
13d8a8ce-074f-487c-b514-fd109f62934b	Baku City Circuit	237e1373-7a68-4841-b541-d17f340aa003
89c1ba61-c777-461e-9abc-c524c34a3b68	Circuit Gilles-Villeneuve	7f49f09b-f1f7-4312-b5d0-e8be78e10f01
0965e59b-fe6f-4799-8239-01f95d665ef4	Silverstone Circuit	849c2feb-208f-40f7-b719-131e34082381
0455078c-93a1-41c1-ac3a-6149908b04fa	Red Bull Ring	958ffab0-d0ec-4e0d-bcf5-990ec9b0f3a3
05c6b668-ef04-4412-9a1f-46cf8c1aabbf	Circuit Paul Ricard	606e0956-7707-43b4-a065-57f59a6b2885
90cf17ad-29f6-43c1-999a-84674b0c758f	Hungaroring	ea893e30-9517-4613-a713-6228782141c9
c85b066e-72fe-41e9-b787-3cb4c6ca5de9	Circuit de Spa Francorchamps	2801dba5-19e5-4557-acce-3829546e7639
6d6cf63e-751d-4153-bff4-354ab70951fb	Circuit Zandvoort	b16cab89-6df3-4e96-8858-1ef550e6c946
9d5146df-6054-4238-bb63-897d1f4dcc2b	Autodromo Nazionale Monza	87afcfd4-a08c-47bf-8d96-e903b34f6c39
bd9bf718-17d4-4f9b-b3ee-5f95b6155611	Marina Bay Street Circuit	cdd3df0f-920c-4854-abfe-46dc2bbe683b
71ed29b0-1ec8-4402-8d85-65e1ed6d7eef	Circuit of the Americas	73d7cf5c-6add-46cb-a97a-4757c6b7764c
415d0c44-0804-445c-9579-56fe5e976dd5	Autodromo Hermanos Rodriguez	98d0e3cd-d803-4517-9cdb-7feb82b0422b
9cc0ccc4-504c-423d-a16e-24c951d36a4b	Autódromo José Carlos Pace	9157eea3-8a50-4a8c-a139-8310731c0d65
508affbb-0506-4423-9fe3-725b49d95274	Yas Marina Circuit	ab2b5530-da66-453a-8833-1b1857f3e54c
bf8dc1d2-760f-468f-93ad-046230923184	Suzuka International Racing Course	29e86eef-3917-4352-afbb-9407f33ec479
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
-- Name: driver_standings driver_standings_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.driver_standings
    ADD CONSTRAINT driver_standings_pkey PRIMARY KEY (id);


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
-- Name: racers racers_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.racers
    ADD CONSTRAINT racers_pkey PRIMARY KEY (id);


--
-- Name: results results_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.results
    ADD CONSTRAINT results_pkey PRIMARY KEY (id);


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
-- Name: team_standings team_standings_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.team_standings
    ADD CONSTRAINT team_standings_pkey PRIMARY KEY (id);


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
-- Name: driver_standing_event_driver_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX driver_standing_event_driver_idx ON public.driver_standings USING btree (event, driver);


--
-- Name: event_season_label_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX event_season_label_idx ON public.events USING btree (season, label);


--
-- Name: event_step_event_type_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX event_step_event_type_idx ON public.event_steps USING btree (event, type);


--
-- Name: racer_car_driver_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX racer_car_driver_idx ON public.racers USING btree (car, driver);


--
-- Name: result_event_step_position_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX result_event_step_position_idx ON public.results USING btree (event_step, "position");


--
-- Name: result_event_step_racer_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX result_event_step_racer_idx ON public.results USING btree (event_step, racer);


--
-- Name: step_type_label_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX step_type_label_idx ON public.step_types USING btree (label);


--
-- Name: team_name_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX team_name_idx ON public.teams USING btree (name);


--
-- Name: team_standing_event_team_idx; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX team_standing_event_team_idx ON public.team_standings USING btree (event, team);


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
-- Name: driver_standings fk_driver_standings_driver; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.driver_standings
    ADD CONSTRAINT fk_driver_standings_driver FOREIGN KEY (driver) REFERENCES public.drivers(id);


--
-- Name: driver_standings fk_driver_standings_event; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.driver_standings
    ADD CONSTRAINT fk_driver_standings_event FOREIGN KEY (event) REFERENCES public.events(id);


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
-- Name: racers fk_racer_car; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.racers
    ADD CONSTRAINT fk_racer_car FOREIGN KEY (car) REFERENCES public.cars(id);


--
-- Name: racers fk_racer_driver; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.racers
    ADD CONSTRAINT fk_racer_driver FOREIGN KEY (driver) REFERENCES public.drivers(id);


--
-- Name: results fk_result_event_step; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.results
    ADD CONSTRAINT fk_result_event_step FOREIGN KEY (event_step) REFERENCES public.event_steps(id);


--
-- Name: results fk_result_racer; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.results
    ADD CONSTRAINT fk_result_racer FOREIGN KEY (racer) REFERENCES public.racers(id);


--
-- Name: teams fk_team_country; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT fk_team_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- Name: team_standings fk_team_standings_driver; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.team_standings
    ADD CONSTRAINT fk_team_standings_driver FOREIGN KEY (team) REFERENCES public.teams(id);


--
-- Name: team_standings fk_team_standings_event; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.team_standings
    ADD CONSTRAINT fk_team_standings_event FOREIGN KEY (event) REFERENCES public.events(id);


--
-- Name: venues fk_venue_country; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.venues
    ADD CONSTRAINT fk_venue_country FOREIGN KEY (country) REFERENCES public.countries(id);


--
-- PostgreSQL database dump complete
--

