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

INSERT INTO public.championships (id, name, slug) VALUES ('f4618d91-2df9-4a39-b857-7b751b27111a', 'Formula One', 'formula1');


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.countries (id, code) VALUES ('db5dc596-45b7-4586-9ee3-95221f0ccb84', 'bh');
INSERT INTO public.countries (id, code) VALUES ('ab04aa06-b392-4f16-8615-afdd2bf561bc', 'sa');
INSERT INTO public.countries (id, code) VALUES ('b94997db-940a-4ddc-a133-a4140d168b94', 'au');
INSERT INTO public.countries (id, code) VALUES ('87afcfd4-a08c-47bf-8d96-e903b34f6c39', 'it');
INSERT INTO public.countries (id, code) VALUES ('73d7cf5c-6add-46cb-a97a-4757c6b7764c', 'us');
INSERT INTO public.countries (id, code) VALUES ('0a7c4ba4-b3a0-4219-a5bd-6466750441a5', 'es');
INSERT INTO public.countries (id, code) VALUES ('5ef1a30f-7f85-493a-8db9-265c10c435d7', 'mc');
INSERT INTO public.countries (id, code) VALUES ('237e1373-7a68-4841-b541-d17f340aa003', 'az');
INSERT INTO public.countries (id, code) VALUES ('7f49f09b-f1f7-4312-b5d0-e8be78e10f01', 'ca');
INSERT INTO public.countries (id, code) VALUES ('849c2feb-208f-40f7-b719-131e34082381', 'gb');
INSERT INTO public.countries (id, code) VALUES ('958ffab0-d0ec-4e0d-bcf5-990ec9b0f3a3', 'at');
INSERT INTO public.countries (id, code) VALUES ('606e0956-7707-43b4-a065-57f59a6b2885', 'fr');
INSERT INTO public.countries (id, code) VALUES ('ea893e30-9517-4613-a713-6228782141c9', 'hu');
INSERT INTO public.countries (id, code) VALUES ('2801dba5-19e5-4557-acce-3829546e7639', 'be');
INSERT INTO public.countries (id, code) VALUES ('b16cab89-6df3-4e96-8858-1ef550e6c946', 'nl');
INSERT INTO public.countries (id, code) VALUES ('cdd3df0f-920c-4854-abfe-46dc2bbe683b', 'sg');
INSERT INTO public.countries (id, code) VALUES ('29e86eef-3917-4352-afbb-9407f33ec479', 'jp');
INSERT INTO public.countries (id, code) VALUES ('98d0e3cd-d803-4517-9cdb-7feb82b0422b', 'mx');
INSERT INTO public.countries (id, code) VALUES ('9157eea3-8a50-4a8c-a139-8310731c0d65', 'br');
INSERT INTO public.countries (id, code) VALUES ('ab2b5530-da66-453a-8833-1b1857f3e54c', 'ae');
INSERT INTO public.countries (id, code) VALUES ('e0ef3345-7693-43c5-95ef-039d1de894a7', 'fl');
INSERT INTO public.countries (id, code) VALUES ('e9b31ca6-40ee-4860-8cc3-55c6951f3a81', 'cn');
INSERT INTO public.countries (id, code) VALUES ('2546e6e8-2d5f-4730-8f3d-7346c34991b7', 'th');
INSERT INTO public.countries (id, code) VALUES ('c1400d2e-b38f-4562-a019-2f2d82f37d01', 'de');
INSERT INTO public.countries (id, code) VALUES ('6f4379bb-5c80-4b10-a225-dc70d51f154c', 'dk');
INSERT INTO public.countries (id, code) VALUES ('0af62fc1-f778-4488-9bc6-63ab9d31c7c7', 'ch');


--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.seasons (id, championship, year) VALUES ('28137908-06e4-4346-b309-c4c04dda4e10', 'f4618d91-2df9-4a39-b857-7b751b27111a', 2022);


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.teams (id, name, image, country) VALUES ('a050a229-f93a-49f8-9209-8ce4297f6b26', 'Mercedes', '/mercedes.png', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.teams (id, name, image, country) VALUES ('0521d82c-77a7-4d46-81f6-ab3741c3e554', 'Red Bull Racing', '/redbullracing.png', '958ffab0-d0ec-4e0d-bcf5-990ec9b0f3a3');
INSERT INTO public.teams (id, name, image, country) VALUES ('664150ac-aeb0-4e06-a35c-c4a6c102cb70', 'Ferrari', '/ferrari.png', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.teams (id, name, image, country) VALUES ('411baf4f-a7f5-465c-8acd-d4d649b4eaff', 'Alpine', '/alpine.png', '606e0956-7707-43b4-a065-57f59a6b2885');
INSERT INTO public.teams (id, name, image, country) VALUES ('9d219474-9719-471e-b2f7-0ccc0f666754', 'McLaren', '/mclaren.png', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.teams (id, name, image, country) VALUES ('08903254-d543-482e-a358-e6129a3f4ffe', 'Williams', '/williams.png', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.teams (id, name, image, country) VALUES ('91793127-a828-4bac-870e-b781f94a8bc8', 'Aston Martin', '/astonmartin.png', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.teams (id, name, image, country) VALUES ('4575090f-e9e7-4a70-802b-f8cdc94f1cd3', 'Haas', '/haas.png', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.teams (id, name, image, country) VALUES ('287c8e13-af66-42da-8df4-2e79560afb2c', 'Alpha Tauri', '/alphatauri.png', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.teams (id, name, image, country) VALUES ('433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d', 'Alfa Romeo Racing', '/alfaromeoracing.png', '0af62fc1-f778-4488-9bc6-63ab9d31c7c7');


--
-- Data for Name: cars; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.cars (id, number, team, season) VALUES ('9091a5af-b588-4632-9251-66654d35f77d', 1, '0521d82c-77a7-4d46-81f6-ab3741c3e554', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('5b927c77-32da-48ac-b6f3-c10f7e042aac', 3, '9d219474-9719-471e-b2f7-0ccc0f666754', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('ab4c09bb-e2cd-4e06-9c59-372fd8365736', 4, '9d219474-9719-471e-b2f7-0ccc0f666754', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('242e5a1a-e706-4824-905a-977767d0bb71', 5, '91793127-a828-4bac-870e-b781f94a8bc8', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('978eba5d-481b-4c9e-b44d-90f657a882f8', 6, '08903254-d543-482e-a358-e6129a3f4ffe', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('10977814-6cf1-4088-a5b8-b8e2fa70d0d4', 10, '287c8e13-af66-42da-8df4-2e79560afb2c', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('ecc7854d-5aae-42e7-a1d7-19081677f431', 11, '0521d82c-77a7-4d46-81f6-ab3741c3e554', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('5d9a78a4-7cd5-4452-8dc4-d054d6786daa', 14, '411baf4f-a7f5-465c-8acd-d4d649b4eaff', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('ada74a80-0a02-43a3-95ad-fd00dfef1683', 16, '664150ac-aeb0-4e06-a35c-c4a6c102cb70', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('782bf361-8ecc-4da3-b097-a97e0c5e4b48', 18, '91793127-a828-4bac-870e-b781f94a8bc8', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('6bd4fc55-89fd-4a61-9fa9-bbfbbdfd2de7', 20, '4575090f-e9e7-4a70-802b-f8cdc94f1cd3', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('4401843f-8fb1-40c0-8256-34086a0823d5', 22, '287c8e13-af66-42da-8df4-2e79560afb2c', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('bd096779-7175-4b01-9cd8-4d7393eb556b', 23, '08903254-d543-482e-a358-e6129a3f4ffe', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('8ed1a44c-063c-480c-8881-ff8162341f58', 24, '433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('4eae9d76-8c8a-4038-ae0f-f8439962d04d', 31, '411baf4f-a7f5-465c-8acd-d4d649b4eaff', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('4b3c6596-3b68-449f-8883-492bf3792cc8', 44, 'a050a229-f93a-49f8-9209-8ce4297f6b26', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('1ef5aca1-e0bc-4252-942b-d8a53ea51ca0', 47, '4575090f-e9e7-4a70-802b-f8cdc94f1cd3', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('c69289d7-1fa4-4d5d-9062-d4e8c9006359', 55, '664150ac-aeb0-4e06-a35c-c4a6c102cb70', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('b56ede6e-8211-492f-b639-762ec367e0dc', 63, 'a050a229-f93a-49f8-9209-8ce4297f6b26', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('0e968bbf-c046-4116-9409-19c65ad381b3', 77, '433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('0b2b5f17-6e4d-4486-8f10-2336d392dddc', 27, '91793127-a828-4bac-870e-b781f94a8bc8', '28137908-06e4-4346-b309-c4c04dda4e10');
INSERT INTO public.cars (id, number, team, season) VALUES ('953f13a4-5f6b-4da6-a23a-147d7e27739c', 45, '08903254-d543-482e-a358-e6129a3f4ffe', '28137908-06e4-4346-b309-c4c04dda4e10');


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.drivers (id, name, firstname, country) VALUES ('72dbaa68-4dc3-4087-90b0-8153cb441738', 'Hamilton', 'Lewis', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('494f6b1d-7622-4032-bf04-182a0dee404e', 'Russell', 'George', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('376f88ca-b07a-4802-b117-799421598d03', 'Verstappen', 'Max', 'b16cab89-6df3-4e96-8858-1ef550e6c946');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('59dc6702-fde8-4b2f-b04e-1a0f8ffebe38', 'Perez', 'Sergio', '98d0e3cd-d803-4517-9cdb-7feb82b0422b');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('886072d0-3f71-44d9-a28e-5d1120b34239', 'Leclerc', 'Charles', '5ef1a30f-7f85-493a-8db9-265c10c435d7');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('5db217dc-d298-479e-ac24-0e2e0178d30a', 'Sainz', 'Carlos', '0a7c4ba4-b3a0-4219-a5bd-6466750441a5');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96', 'Alonso', 'Fernando', '0a7c4ba4-b3a0-4219-a5bd-6466750441a5');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('b8242bfc-831e-41d3-99ca-a15906bef28b', 'Ocon', 'Esteban', '606e0956-7707-43b4-a065-57f59a6b2885');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('f4c60528-4f64-47d0-b460-15c0ec4d69fe', 'Norris', 'Lando', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('f9157781-1615-4d1e-9b58-6298ea1c9200', 'Ricciardo', 'Daniel', 'b94997db-940a-4ddc-a133-a4140d168b94');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('d98aaa41-6948-4216-9585-c84faedcd17c', 'Gasly', 'Pierre', '606e0956-7707-43b4-a065-57f59a6b2885');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('4da2cb59-5bc4-4502-983c-8ab5ba156f78', 'Tsunoda', 'Yuki', '29e86eef-3917-4352-afbb-9407f33ec479');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('93cd5944-2646-4681-ba0c-fe24be31a2d7', 'Bottas', 'Valtteri', 'e0ef3345-7693-43c5-95ef-039d1de894a7');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('368de2ea-cc01-412e-a670-65f3512daf10', 'Guanyu', 'Zhou', 'e9b31ca6-40ee-4860-8cc3-55c6951f3a81');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('043f15b7-7e92-4a7e-8e2d-08db6ae3c283', 'Albon', 'Alex', '2546e6e8-2d5f-4730-8f3d-7346c34991b7');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('8345df6c-8d1a-4de8-8616-23869b2b316f', 'Latifi', 'Nicholas', '7f49f09b-f1f7-4312-b5d0-e8be78e10f01');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('b871589d-32f5-43c6-9cf6-9750b8748498', 'Vettel', 'Sebastian', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('b480ff53-a91e-49bb-8de2-ba673501982f', 'Stroll', 'Lance', '7f49f09b-f1f7-4312-b5d0-e8be78e10f01');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('92bba624-1864-4053-8c7f-7f1ea6709063', 'Schumacher', 'Mick', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('caf2d7ff-8372-4043-b79d-e2062d799da6', 'Magnussen', 'Kevin', '6f4379bb-5c80-4b10-a225-dc70d51f154c');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('88038686-7a0e-4d23-8043-93e56e48a804', 'Hulkenberg', 'Nico', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('f3296964-0bf7-4ad6-aa47-3fc349d66720', 'de Vries', 'Nyck', 'b16cab89-6df3-4e96-8858-1ef550e6c946');


--
-- Data for Name: driver_moves; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('16743bef-907e-4d3a-95dc-6f4bc0588f2c', '376f88ca-b07a-4802-b117-799421598d03', '9091a5af-b588-4632-9251-66654d35f77d', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('599e91ab-4680-4fc1-b232-0591ffac9b28', 'f9157781-1615-4d1e-9b58-6298ea1c9200', '5b927c77-32da-48ac-b6f3-c10f7e042aac', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('49c6078a-eefe-4616-92c2-b0862e2322cb', 'f4c60528-4f64-47d0-b460-15c0ec4d69fe', 'ab4c09bb-e2cd-4e06-9c59-372fd8365736', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('3b9cd10b-bd3f-446b-8c4a-db41ae633817', 'b871589d-32f5-43c6-9cf6-9750b8748498', '242e5a1a-e706-4824-905a-977767d0bb71', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('a573977c-673a-426a-8a62-9e7c9ced9891', '8345df6c-8d1a-4de8-8616-23869b2b316f', '978eba5d-481b-4c9e-b44d-90f657a882f8', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('2ffe1c0a-ba50-4cbc-bca4-3c142d3f822e', '8345df6c-8d1a-4de8-8616-23869b2b316f', '978eba5d-481b-4c9e-b44d-90f657a882f8', '2022-09-12 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('b7c85850-154d-425e-acfe-d0668ee87e74', 'd98aaa41-6948-4216-9585-c84faedcd17c', '10977814-6cf1-4088-a5b8-b8e2fa70d0d4', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('831732b5-031b-4b82-a05e-32465b1d557f', '59dc6702-fde8-4b2f-b04e-1a0f8ffebe38', 'ecc7854d-5aae-42e7-a1d7-19081677f431', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('8634a1ee-9de5-4678-b2e3-cf7bc9f54579', '0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96', '5d9a78a4-7cd5-4452-8dc4-d054d6786daa', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('19d0be81-7660-4cdf-b8e2-8b6a6584961d', '886072d0-3f71-44d9-a28e-5d1120b34239', 'ada74a80-0a02-43a3-95ad-fd00dfef1683', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('93c7790f-d413-4420-aca4-d68f0fad6bfb', 'b480ff53-a91e-49bb-8de2-ba673501982f', '782bf361-8ecc-4da3-b097-a97e0c5e4b48', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('641fd1a6-3004-4e25-be5a-1d57c4199d1e', 'caf2d7ff-8372-4043-b79d-e2062d799da6', '6bd4fc55-89fd-4a61-9fa9-bbfbbdfd2de7', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('d10136d0-d679-41da-9b2e-cf23a33e1096', '4da2cb59-5bc4-4502-983c-8ab5ba156f78', '4401843f-8fb1-40c0-8256-34086a0823d5', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('14d11666-fb50-4893-9452-232170f1b5bc', '043f15b7-7e92-4a7e-8e2d-08db6ae3c283', 'bd096779-7175-4b01-9cd8-4d7393eb556b', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('74f611b3-aa4a-41df-98da-4e4c33a0c011', '368de2ea-cc01-412e-a670-65f3512daf10', '8ed1a44c-063c-480c-8881-ff8162341f58', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('7fce524e-d625-4e58-87b0-c24141d0d679', 'b8242bfc-831e-41d3-99ca-a15906bef28b', '4eae9d76-8c8a-4038-ae0f-f8439962d04d', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('f66c4aad-f065-483a-b894-312f6e4b77cb', '72dbaa68-4dc3-4087-90b0-8153cb441738', '4b3c6596-3b68-449f-8883-492bf3792cc8', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('0b8a29ef-b73a-4015-8b76-3d9ba84318b8', '92bba624-1864-4053-8c7f-7f1ea6709063', '1ef5aca1-e0bc-4252-942b-d8a53ea51ca0', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('be112b60-4922-40e0-8e6e-f26a976ea568', '5db217dc-d298-479e-ac24-0e2e0178d30a', 'c69289d7-1fa4-4d5d-9062-d4e8c9006359', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('15677068-3205-47a3-a86a-45a08c407e98', '494f6b1d-7622-4032-bf04-182a0dee404e', 'b56ede6e-8211-492f-b639-762ec367e0dc', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('8ce73ef3-df08-4b24-b725-3972fea0d6ba', '93cd5944-2646-4681-ba0c-fe24be31a2d7', '0e968bbf-c046-4116-9409-19c65ad381b3', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('34170647-59f6-442e-95cd-b5524bf6e031', '88038686-7a0e-4d23-8043-93e56e48a804', '0b2b5f17-6e4d-4486-8f10-2336d392dddc', '2022-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('0b92fa88-5708-4721-af62-4dd193cdfd18', 'f3296964-0bf7-4ad6-aa47-3fc349d66720', '953f13a4-5f6b-4da6-a23a-147d7e27739c', '2022-09-05 00:00:00');


--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.venues (id, name, country) VALUES ('409bb6eb-aaaf-48b9-9012-07bc9628ffe2', 'Bahrain International Circuit', 'db5dc596-45b7-4586-9ee3-95221f0ccb84');
INSERT INTO public.venues (id, name, country) VALUES ('e400d424-2bee-47ab-a1f3-637823129f88', 'Jeddah Street Circuit', 'ab04aa06-b392-4f16-8615-afdd2bf561bc');
INSERT INTO public.venues (id, name, country) VALUES ('a9cbe6b1-e38f-417c-b206-824fc7ae1f7c', 'Melbourne Grand Prix Circuit', 'b94997db-940a-4ddc-a133-a4140d168b94');
INSERT INTO public.venues (id, name, country) VALUES ('ef313471-20cc-42c3-91eb-9d079a7f7b03', 'Autodromo Internazionale Enzo e Dino Ferrari', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.venues (id, name, country) VALUES ('c2116751-22ca-40d1-bf2f-2fd30ab0cd18', 'Miami International Autodrome', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('d2f04839-e8de-484a-98f7-beda803d4239', 'Circuit de Barcelona-Catalunya', '0a7c4ba4-b3a0-4219-a5bd-6466750441a5');
INSERT INTO public.venues (id, name, country) VALUES ('7242a078-f76c-4166-8658-70eb8fc909a0', 'Circuit de Monaco', '5ef1a30f-7f85-493a-8db9-265c10c435d7');
INSERT INTO public.venues (id, name, country) VALUES ('13d8a8ce-074f-487c-b514-fd109f62934b', 'Baku City Circuit', '237e1373-7a68-4841-b541-d17f340aa003');
INSERT INTO public.venues (id, name, country) VALUES ('89c1ba61-c777-461e-9abc-c524c34a3b68', 'Circuit Gilles-Villeneuve', '7f49f09b-f1f7-4312-b5d0-e8be78e10f01');
INSERT INTO public.venues (id, name, country) VALUES ('0965e59b-fe6f-4799-8239-01f95d665ef4', 'Silverstone Circuit', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.venues (id, name, country) VALUES ('0455078c-93a1-41c1-ac3a-6149908b04fa', 'Red Bull Ring', '958ffab0-d0ec-4e0d-bcf5-990ec9b0f3a3');
INSERT INTO public.venues (id, name, country) VALUES ('05c6b668-ef04-4412-9a1f-46cf8c1aabbf', 'Circuit Paul Ricard', '606e0956-7707-43b4-a065-57f59a6b2885');
INSERT INTO public.venues (id, name, country) VALUES ('90cf17ad-29f6-43c1-999a-84674b0c758f', 'Hungaroring', 'ea893e30-9517-4613-a713-6228782141c9');
INSERT INTO public.venues (id, name, country) VALUES ('c85b066e-72fe-41e9-b787-3cb4c6ca5de9', 'Circuit de Spa Francorchamps', '2801dba5-19e5-4557-acce-3829546e7639');
INSERT INTO public.venues (id, name, country) VALUES ('6d6cf63e-751d-4153-bff4-354ab70951fb', 'Circuit Zandvoort', 'b16cab89-6df3-4e96-8858-1ef550e6c946');
INSERT INTO public.venues (id, name, country) VALUES ('9d5146df-6054-4238-bb63-897d1f4dcc2b', 'Autodromo Nazionale Monza', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.venues (id, name, country) VALUES ('bd9bf718-17d4-4f9b-b3ee-5f95b6155611', 'Marina Bay Street Circuit', 'cdd3df0f-920c-4854-abfe-46dc2bbe683b');
INSERT INTO public.venues (id, name, country) VALUES ('71ed29b0-1ec8-4402-8d85-65e1ed6d7eef', 'Circuit of the Americas', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('415d0c44-0804-445c-9579-56fe5e976dd5', 'Autodromo Hermanos Rodriguez', '98d0e3cd-d803-4517-9cdb-7feb82b0422b');
INSERT INTO public.venues (id, name, country) VALUES ('9cc0ccc4-504c-423d-a16e-24c951d36a4b', 'Autódromo José Carlos Pace', '9157eea3-8a50-4a8c-a139-8310731c0d65');
INSERT INTO public.venues (id, name, country) VALUES ('508affbb-0506-4423-9fe3-725b49d95274', 'Yas Marina Circuit', 'ab2b5530-da66-453a-8833-1b1857f3e54c');
INSERT INTO public.venues (id, name, country) VALUES ('bf8dc1d2-760f-468f-93ad-046230923184', 'Suzuka International Racing Course', '29e86eef-3917-4352-afbb-9407f33ec479');


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.events (id, season, venue, index, label) VALUES ('51d9d255-07ec-4934-a314-d8f47302d0b7', '28137908-06e4-4346-b309-c4c04dda4e10', '409bb6eb-aaaf-48b9-9012-07bc9628ffe2', 0, 'Gulf Air Bahrain Grand Prix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('d41a2e1d-ac4e-42e9-a72d-54abd0620887', '28137908-06e4-4346-b309-c4c04dda4e10', 'e400d424-2bee-47ab-a1f3-637823129f88', 1, 'STC Saudi Arabian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('31939543-3a9f-45f4-aa4e-78f8f2aade70', '28137908-06e4-4346-b309-c4c04dda4e10', 'a9cbe6b1-e38f-417c-b206-824fc7ae1f7c', 2, 'Heineken Australian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('b7941e54-d8a0-4785-85b4-36316bff4b71', '28137908-06e4-4346-b309-c4c04dda4e10', 'ef313471-20cc-42c3-91eb-9d079a7f7b03', 3, 'Rolex Emilia Romagna GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2f11aff6-baae-4bd6-9426-678cc33281e0', '28137908-06e4-4346-b309-c4c04dda4e10', 'c2116751-22ca-40d1-bf2f-2fd30ab0cd18', 4, 'Crypto.com Miami GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('b3f67443-31cb-4263-84b3-e861ddba4e4b', '28137908-06e4-4346-b309-c4c04dda4e10', 'd2f04839-e8de-484a-98f7-beda803d4239', 5, 'Pirelli Spanish GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('6afbb5e2-e169-411f-9f30-e1919f4fbb6a', '28137908-06e4-4346-b309-c4c04dda4e10', '7242a078-f76c-4166-8658-70eb8fc909a0', 6, 'Monaco GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('226da250-a350-457d-864d-ebe8f47d9f73', '28137908-06e4-4346-b309-c4c04dda4e10', '13d8a8ce-074f-487c-b514-fd109f62934b', 7, 'Azerbaijan GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('967cb75d-60f6-4998-9e60-ba45e94c3ee8', '28137908-06e4-4346-b309-c4c04dda4e10', '89c1ba61-c777-461e-9abc-c524c34a3b68', 8, 'AWS Canada GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('5565ab3f-ee96-474f-a266-b1a83885aaa9', '28137908-06e4-4346-b309-c4c04dda4e10', '0965e59b-fe6f-4799-8239-01f95d665ef4', 9, 'Lenovo British GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('f5aabd63-10dc-4bf7-908f-b78b48cfe866', '28137908-06e4-4346-b309-c4c04dda4e10', '0455078c-93a1-41c1-ac3a-6149908b04fa', 10, 'Rolex Austrian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', '28137908-06e4-4346-b309-c4c04dda4e10', '05c6b668-ef04-4412-9a1f-46cf8c1aabbf', 11, 'Lenovo French GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('20f33cc2-edf0-462d-813f-3c88853c5935', '28137908-06e4-4346-b309-c4c04dda4e10', '90cf17ad-29f6-43c1-999a-84674b0c758f', 12, 'Aramco Hungarian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', '28137908-06e4-4346-b309-c4c04dda4e10', 'c85b066e-72fe-41e9-b787-3cb4c6ca5de9', 13, 'Rolex Belgian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('1c6c16fb-e472-48d3-8bc6-7000a342f881', '28137908-06e4-4346-b309-c4c04dda4e10', '6d6cf63e-751d-4153-bff4-354ab70951fb', 14, 'Heineken Dutch GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', '28137908-06e4-4346-b309-c4c04dda4e10', '9d5146df-6054-4238-bb63-897d1f4dcc2b', 15, 'Pirelli Italian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('1ca163e0-f93a-41e0-b935-ebd1ea86bf97', '28137908-06e4-4346-b309-c4c04dda4e10', 'bd9bf718-17d4-4f9b-b3ee-5f95b6155611', 16, 'Singapore Air Singapore GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('44d55619-b5ca-4d9d-b33d-293cd87272af', '28137908-06e4-4346-b309-c4c04dda4e10', 'bf8dc1d2-760f-468f-93ad-046230923184', 17, 'Honda Japanese GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('c83f5cc3-47ed-46b6-baeb-3bfe254b5647', '28137908-06e4-4346-b309-c4c04dda4e10', '71ed29b0-1ec8-4402-8d85-65e1ed6d7eef', 18, 'Aramco United States GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('384ca8e9-9563-4908-b487-024f0653a0cc', '28137908-06e4-4346-b309-c4c04dda4e10', '415d0c44-0804-445c-9579-56fe5e976dd5', 19, 'Mexico GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('9165f124-a621-476f-a419-d1fc4727ac27', '28137908-06e4-4346-b309-c4c04dda4e10', '9cc0ccc4-504c-423d-a16e-24c951d36a4b', 20, 'Heineken Brazil GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('debaeb4c-92e5-4309-9004-e85727ff7b12', '28137908-06e4-4346-b309-c4c04dda4e10', '508affbb-0506-4423-9fe3-725b49d95274', 21, 'Etihad Airways Abu Dhabi GP');


--
-- Data for Name: driver_standings; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: step_types; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.step_types (id, label) VALUES ('a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', 'Practice 1');
INSERT INTO public.step_types (id, label) VALUES ('2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', 'Practice 2');
INSERT INTO public.step_types (id, label) VALUES ('3da29b08-635d-4400-97b5-261978b92ef1', 'Practice 3');
INSERT INTO public.step_types (id, label) VALUES ('3c56e36c-14f8-4c69-8976-c22105359e5a', 'Qualifying');
INSERT INTO public.step_types (id, label) VALUES ('b3418e4c-73dc-4304-b45a-c64bc9fb3f27', 'Race');
INSERT INTO public.step_types (id, label) VALUES ('2848eaca-c593-4b97-95de-6b71c114b0b5', 'Sprint Qualifying');


--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4e1fbe33-4680-48ad-81d7-264d437bce19', '51d9d255-07ec-4934-a314-d8f47302d0b7', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-03-18 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('87e39c11-e094-40e7-8ccd-487a04ed4864', '51d9d255-07ec-4934-a314-d8f47302d0b7', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-03-18 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('727ce9cc-6792-4133-a3c3-bf3d3dcb2481', '51d9d255-07ec-4934-a314-d8f47302d0b7', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-03-19 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5d9e5f7b-e9d1-49ba-950d-a702e367f718', '51d9d255-07ec-4934-a314-d8f47302d0b7', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-03-19 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('335e83b4-82ec-4a83-b0ad-5e986fa70fb6', '51d9d255-07ec-4934-a314-d8f47302d0b7', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-03-20 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7408697f-df4a-43f0-824a-97df3a61f4c0', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-03-25 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('86438d95-728a-4885-b6c9-7d1ba7dffe1d', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-03-25 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ee73fc10-a441-4617-b0c5-5f3a22dd6eb6', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-03-26 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('15b4688c-1d11-41b3-bfa2-d1814a6eeb77', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-03-26 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('66ce89ce-6150-4db7-928e-80e7dccfdb87', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-03-27 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('0775b751-252c-48ec-be72-0f8b46b32e2f', '31939543-3a9f-45f4-aa4e-78f8f2aade70', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-04-08 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ff84931d-95b4-4d31-943f-497afa69ce23', '226da250-a350-457d-864d-ebe8f47d9f73', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-06-11 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c3f0274c-ba39-4884-a93d-186979bf134a', '31939543-3a9f-45f4-aa4e-78f8f2aade70', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-04-08 03:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('caefc3b3-f622-44c1-beaf-9c267406f372', '31939543-3a9f-45f4-aa4e-78f8f2aade70', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-04-09 03:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2b461fad-ce83-4bf6-8f3f-f74241ecaf84', '31939543-3a9f-45f4-aa4e-78f8f2aade70', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-04-09 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c6e5d69a-9dd5-40c2-8245-abe5eb311476', '31939543-3a9f-45f4-aa4e-78f8f2aade70', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-04-10 05:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('0207dcae-1a26-449e-8207-0e6df01ed960', 'b7941e54-d8a0-4785-85b4-36316bff4b71', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-04-22 11:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bddbb6b4-e15a-48b8-9aca-a0d028bb7201', 'b7941e54-d8a0-4785-85b4-36316bff4b71', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-04-22 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('be5c528d-cba2-479f-8b04-dcd210407154', 'b7941e54-d8a0-4785-85b4-36316bff4b71', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-04-23 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('39c033cc-8b28-4587-ad95-4ce334e5c3bc', 'b7941e54-d8a0-4785-85b4-36316bff4b71', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2022-04-23 14:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e9a88ef1-6672-46ca-bf46-1957e0046fb0', 'b7941e54-d8a0-4785-85b4-36316bff4b71', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-04-24 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('83058919-fc03-4e0b-a20b-bdd71a338378', '2f11aff6-baae-4bd6-9426-678cc33281e0', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-05-06 18:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c5588b1a-7318-468b-88a9-f06e2ac3bdf0', '2f11aff6-baae-4bd6-9426-678cc33281e0', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-05-06 21:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('57456d37-541a-447e-96f7-027bb18209cc', '2f11aff6-baae-4bd6-9426-678cc33281e0', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-05-07 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('92eaaf65-61c8-4fb0-8c45-791ba43da5e1', '2f11aff6-baae-4bd6-9426-678cc33281e0', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-05-07 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('74b8e5dd-838c-49d4-8dff-77b7dc940b98', '2f11aff6-baae-4bd6-9426-678cc33281e0', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-05-08 19:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('04775cb5-7fc4-4fb6-8a87-6a9a5b0c003b', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-05-20 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ee6b750a-83eb-4d12-909e-b1deb292111e', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-05-20 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ed57ee09-030b-483b-92d9-d63a43b7b9f8', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-05-22 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('61f52072-6048-4500-89a9-e05bab0f5b78', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-05-21 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('65200673-c8a9-4100-aea8-3d8ec3e07d0d', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-05-21 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('491f76bd-9487-49a5-a934-98536c4da061', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-05-27 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2905ef05-c466-43f9-9f0a-c455d47b1ac3', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-05-27 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bc346ba8-6f3d-4cf6-80a5-67a1700c2b90', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-05-28 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('381d713b-9c03-4a67-8886-ff5dc1947d27', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-05-28 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b3e9ef4c-9a22-4525-930d-a56cdecad111', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-05-29 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a04185d0-6a3c-475d-bfa1-3ec50e279396', '226da250-a350-457d-864d-ebe8f47d9f73', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-06-10 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5b8afd7b-e533-43a3-9f7e-7fe58ac29d11', '226da250-a350-457d-864d-ebe8f47d9f73', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-06-10 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('442c53cd-4109-42da-a20f-a50b72fe682d', '226da250-a350-457d-864d-ebe8f47d9f73', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-06-11 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a8ebaa61-053b-4c39-b9bd-be494c5aa82a', '226da250-a350-457d-864d-ebe8f47d9f73', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-06-12 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8496286c-bc26-4d7a-ba4e-2b89731c7eba', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-06-17 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2530f627-e816-42c1-9255-bb5a23f4b923', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-06-17 21:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('cf39e5ab-4d85-4d18-9d0c-e9aeb46d1095', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-06-18 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('11f50c3b-2c72-4174-af53-8eef221e0ff4', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-06-18 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('99d4c260-715b-4af2-b82b-1a6beac17a6d', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-06-19 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c91bf7ae-670a-4248-a571-61cd3a154800', '5565ab3f-ee96-474f-a266-b1a83885aaa9', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-07-01 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c3aabafa-e984-4d37-8618-11dcf2e4a558', '5565ab3f-ee96-474f-a266-b1a83885aaa9', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-07-01 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7bc6c9da-6f55-4412-b947-a03279542225', '5565ab3f-ee96-474f-a266-b1a83885aaa9', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-07-02 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3a6228dd-9a7b-420d-928d-d8c4b6ee62a0', '5565ab3f-ee96-474f-a266-b1a83885aaa9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-02 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2fc09c78-4025-4e95-b8f0-84aaa413521c', '5565ab3f-ee96-474f-a266-b1a83885aaa9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-03 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('0d7a764d-e6d1-4d0f-87c7-cc6b8aaa9e63', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-08 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('02524657-b6a6-44d1-afb6-9349574615b5', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-07-09 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('6c65d484-4acb-4cbb-95f0-6ba00187c6af', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2022-07-09 14:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('50247b27-8dec-4684-be79-ce39fa564109', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-10 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('90837dd0-f9e5-4db3-8c9e-83a280916f5a', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-07-08 11:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('12ffe553-d809-411d-946c-4f1b87135c6e', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-07-22 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4b28bf74-78e2-4947-ab5b-e087ad85f45e', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-07-22 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bf1251d3-f571-478e-8239-71631690a945', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-07-23 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('02b4cc72-9de0-4134-a04d-40a5d4503337', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-23 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('89e06d9b-abd8-423e-aac7-f4e96fe07739', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-24 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('23cdf4be-5631-4cc2-80ab-29fd33252acb', '20f33cc2-edf0-462d-813f-3c88853c5935', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-07-29 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d6383008-429d-4992-a0d8-177bd9fbcc25', '20f33cc2-edf0-462d-813f-3c88853c5935', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-07-29 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('98d581ba-7285-43c9-a039-2000a675b996', '20f33cc2-edf0-462d-813f-3c88853c5935', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-07-30 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c4437881-ff19-4acf-9d70-f3746dd7a944', '20f33cc2-edf0-462d-813f-3c88853c5935', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-30 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7786220c-ffdd-41a1-b86c-b8f289028cc0', '20f33cc2-edf0-462d-813f-3c88853c5935', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-31 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b1249f78-99dc-45e7-8902-42c88648e3af', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-08-26 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3a46a851-e07e-47ce-8377-a7c04d85d416', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-08-26 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8a28823c-a440-496b-a07a-8d1c0f1f3eb4', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-08-27 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('df50d9cc-92ab-46a5-9406-692675c7ed45', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-08-27 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d198694d-26d2-4d83-8df6-aa3ed81c478f', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-08-28 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('f343420c-55b4-4694-a78a-baecd0a7e91a', '1c6c16fb-e472-48d3-8bc6-7000a342f881', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-09-02 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ce1b4779-050f-4b08-8c2d-59295be8f2cd', '1c6c16fb-e472-48d3-8bc6-7000a342f881', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-09-02 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('aca50e76-af81-4787-aa3c-176282c8bcbf', '1c6c16fb-e472-48d3-8bc6-7000a342f881', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-09-03 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1532135e-00d2-4d62-94c4-8f473ff1e9c2', '1c6c16fb-e472-48d3-8bc6-7000a342f881', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-09-03 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b0cae2ba-cb8b-4a89-8973-93b8cbafe728', '1c6c16fb-e472-48d3-8bc6-7000a342f881', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-09-04 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1fbb07bb-29ad-4dee-82df-1142ab693df4', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-09-09 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('374ae7d0-12ff-4525-bf32-d14cd603da6b', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-09-09 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('335e2158-073e-4243-a33e-6f109adb7ff7', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-09-10 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e06f7484-7287-4cd6-a6b5-0b8946f87e8f', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-09-10 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8ca32f26-fe7a-4bad-941f-b403ea958642', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-09-11 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('655b9560-05d2-4e39-ab33-c343082c43ca', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-09-30 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('cf2a0778-853a-4fdb-b7e6-0fd8e2dd8079', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-09-30 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('103fce01-dbd5-4cc1-b847-e61bf1c7fa2e', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-10-01 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('906f9587-2270-4a2d-a832-39720aaea23a', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-01 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('84636143-56dd-45dc-80bd-eb4f08cd2694', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-02 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('677b26b3-5490-47c5-8ac3-4ec94ccf0a15', '44d55619-b5ca-4d9d-b33d-293cd87272af', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-10-07 03:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9f96c34a-d8e4-43ed-9e90-fa484c61362e', '44d55619-b5ca-4d9d-b33d-293cd87272af', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-10-07 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9f3f5572-6054-4609-a0d4-3da086d39add', '44d55619-b5ca-4d9d-b33d-293cd87272af', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-10-08 03:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c22ef212-bc2a-47c5-a24c-7c656c346297', '44d55619-b5ca-4d9d-b33d-293cd87272af', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-08 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('94df2b80-7a55-42e1-aa42-f8cf8b6c7cb7', '44d55619-b5ca-4d9d-b33d-293cd87272af', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-09 05:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b32a150f-3be6-4cf3-9f6f-d5c634925830', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-10-22 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('81c0dcc5-0c66-4bbe-96c4-21e379a07935', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-22 22:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('143b8039-53d9-443e-9fe7-0821253bd841', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-23 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('86696623-af33-41b1-8640-cce56af165de', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-10-21 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('295e284e-bdb3-4802-bd5c-3845686ad1d5', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-10-21 22:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a2152f47-0665-4922-9d1d-1dd6ddffeba1', '384ca8e9-9563-4908-b487-024f0653a0cc', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-10-28 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e124a4cb-f9a3-4f46-a3ff-15cd537447a7', '384ca8e9-9563-4908-b487-024f0653a0cc', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-10-28 21:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1aaac26f-5622-4b95-a8ca-f3c50e888748', '384ca8e9-9563-4908-b487-024f0653a0cc', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-10-29 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('36037f01-d98c-476e-97b4-c78518c7122a', '384ca8e9-9563-4908-b487-024f0653a0cc', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-29 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fdc57e37-65e8-4475-a354-6fc83db52de7', '384ca8e9-9563-4908-b487-024f0653a0cc', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-30 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e8ad2439-7e33-4d6d-935c-2333683b6550', '9165f124-a621-476f-a419-d1fc4727ac27', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-11-11 15:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('904302d8-94b7-4a0b-905c-00fefc028e29', '9165f124-a621-476f-a419-d1fc4727ac27', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-11-11 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('6acaf51c-cbff-49ba-8391-1b8429e881c8', '9165f124-a621-476f-a419-d1fc4727ac27', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-11-12 15:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('cd34909c-cd6f-4c08-9075-9abfb3253931', '9165f124-a621-476f-a419-d1fc4727ac27', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2022-11-12 19:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('423b3b12-f4b5-47d5-ab10-178e478421c4', '9165f124-a621-476f-a419-d1fc4727ac27', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-11-13 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bedaccfa-0a35-4564-8770-7c63bd427c89', 'debaeb4c-92e5-4309-9004-e85727ff7b12', 'a10f354f-b35d-4e0a-88cf-e1a6ebe82d75', '2022-11-18 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('61f05848-a868-4353-af4f-152859914a8e', 'debaeb4c-92e5-4309-9004-e85727ff7b12', '2f1ef80d-3eaa-42b2-87f4-bf36da2d8e06', '2022-11-18 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2f999c42-e3f7-4f51-b6b1-83e2d850181f', 'debaeb4c-92e5-4309-9004-e85727ff7b12', '3da29b08-635d-4400-97b5-261978b92ef1', '2022-11-19 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('acdc2598-3b6a-4ae9-aacd-6e185fe3ae8f', 'debaeb4c-92e5-4309-9004-e85727ff7b12', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-11-19 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('97db111b-44f7-4093-9ed3-267606ec704f', 'debaeb4c-92e5-4309-9004-e85727ff7b12', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-11-20 12:00:00');


--
-- Data for Name: racers; Type: TABLE DATA; Schema: public; Owner: app
--

INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('6faa4747-db3c-4869-8532-d4706ee921be', '9091a5af-b588-4632-9251-66654d35f77d', '376f88ca-b07a-4802-b117-799421598d03', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('f24a8df4-e2d5-4b11-a0ba-2c9eba96abb4', '5b927c77-32da-48ac-b6f3-c10f7e042aac', 'f9157781-1615-4d1e-9b58-6298ea1c9200', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('22e3a5e7-b814-4912-bd23-9b5abb67af4c', 'ab4c09bb-e2cd-4e06-9c59-372fd8365736', 'f4c60528-4f64-47d0-b460-15c0ec4d69fe', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('eac10244-6119-46a1-9541-7a486ec83442', '10977814-6cf1-4088-a5b8-b8e2fa70d0d4', 'd98aaa41-6948-4216-9585-c84faedcd17c', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('cc56f492-ae21-47d5-92db-f0e94fb059ff', 'ecc7854d-5aae-42e7-a1d7-19081677f431', '59dc6702-fde8-4b2f-b04e-1a0f8ffebe38', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('ef9ed0e7-568f-40e3-af6e-1d48ff961a65', '5d9a78a4-7cd5-4452-8dc4-d054d6786daa', '0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('2abfcd71-9956-4ce3-a0a3-2057c811bf78', 'ada74a80-0a02-43a3-95ad-fd00dfef1683', '886072d0-3f71-44d9-a28e-5d1120b34239', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('1006b479-80e7-43fd-a65c-73e7724c803c', '782bf361-8ecc-4da3-b097-a97e0c5e4b48', 'b480ff53-a91e-49bb-8de2-ba673501982f', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('c2ae3859-8655-4ae0-8001-ccc3b627f75c', '6bd4fc55-89fd-4a61-9fa9-bbfbbdfd2de7', 'caf2d7ff-8372-4043-b79d-e2062d799da6', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('514df511-4b82-4b1e-8c0a-1ca7f810ff8f', '4401843f-8fb1-40c0-8256-34086a0823d5', '4da2cb59-5bc4-4502-983c-8ab5ba156f78', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('94b15ca0-6d88-4782-a51d-9d2674f467d4', 'bd096779-7175-4b01-9cd8-4d7393eb556b', '043f15b7-7e92-4a7e-8e2d-08db6ae3c283', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('423422d0-9af2-49d2-9b71-82f5b90ddc42', '8ed1a44c-063c-480c-8881-ff8162341f58', '368de2ea-cc01-412e-a670-65f3512daf10', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('67439855-dfa0-4922-9a01-71cf88c00baa', '4eae9d76-8c8a-4038-ae0f-f8439962d04d', 'b8242bfc-831e-41d3-99ca-a15906bef28b', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('377dc90d-0a50-4826-aaa4-062bb6e23afb', '4b3c6596-3b68-449f-8883-492bf3792cc8', '72dbaa68-4dc3-4087-90b0-8153cb441738', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('31e342a0-5535-4196-8adc-55ee504c5522', '1ef5aca1-e0bc-4252-942b-d8a53ea51ca0', '92bba624-1864-4053-8c7f-7f1ea6709063', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('8928f1bd-57d4-4711-bca4-e242078a92fe', 'c69289d7-1fa4-4d5d-9062-d4e8c9006359', '5db217dc-d298-479e-ac24-0e2e0178d30a', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('0d4d5a06-717a-4447-9e35-5814f3e21047', 'b56ede6e-8211-492f-b639-762ec367e0dc', '494f6b1d-7622-4032-bf04-182a0dee404e', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('1ca83a76-9c12-4609-9508-24b8d7b7fa7a', '0e968bbf-c046-4116-9409-19c65ad381b3', '93cd5944-2646-4681-ba0c-fe24be31a2d7', '2022-01-01 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('cda5215b-aed3-4528-ad43-f751060dcbba', '0b2b5f17-6e4d-4486-8f10-2336d392dddc', '88038686-7a0e-4d23-8043-93e56e48a804', '2022-01-01 00:00:00', '2022-03-25 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('3a96b26d-f495-479a-8ac2-584058e09e26', '242e5a1a-e706-4824-905a-977767d0bb71', 'b871589d-32f5-43c6-9cf6-9750b8748498', '2022-03-26 00:00:00', '2022-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('27426e68-0d4f-428b-85c9-c917cc808ac6', '978eba5d-481b-4c9e-b44d-90f657a882f8', '8345df6c-8d1a-4de8-8616-23869b2b316f', '2022-01-01 00:00:00', '2022-09-04 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('a9532c63-4450-45a6-a128-1df4e9fec5cf', '953f13a4-5f6b-4da6-a23a-147d7e27739c', 'f3296964-0bf7-4ad6-aa47-3fc349d66720', '2022-09-05 00:00:00', '2022-09-11 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('846338c7-fa59-4dde-bba4-52510f6cff2c', '978eba5d-481b-4c9e-b44d-90f657a882f8', '8345df6c-8d1a-4de8-8616-23869b2b316f', '2022-09-12 00:00:00', '2022-12-31 23:59:59');


--
-- Data for Name: results; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- Data for Name: team_standings; Type: TABLE DATA; Schema: public; Owner: app
--



--
-- PostgreSQL database dump complete
--

