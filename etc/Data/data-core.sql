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
-- Data for Name: championships; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

INSERT INTO public.championships (id, name, slug) VALUES ('f4618d91-2df9-4a39-b857-7b751b27111a', 'Formula One', 'formula1');
INSERT INTO public.championships (id, name, slug) VALUES ('e64a940b-e4c3-4caa-948a-dca4b9716c78', 'Moto GP', 'motogp');
INSERT INTO public.championships (id, name, slug) VALUES ('38afa966-116c-4ec1-ae20-590c43977906', 'World Endurance Championship', 'wec');
INSERT INTO public.championships (id, name, slug) VALUES ('24b1d23b-d02b-41f2-82e3-62e73b105691', 'IMSA', 'imsa');
INSERT INTO public.championships (id, name, slug) VALUES ('647fcf89-b1d3-4450-997c-884313c1b259', 'Formula E', 'formulae');
INSERT INTO public.championships (id, name, slug) VALUES ('e5ae8a14-3fa8-4adf-95af-a4f2dd97ff42', 'Formula 2', 'formula2');
INSERT INTO public.championships (id, name, slug) VALUES ('05b1d906-3a15-4a88-a623-0e10b2c2e88c', 'GT World Challenge', 'gtworldchallenge');
INSERT INTO public.championships (id, name, slug) VALUES ('bc702072-ce7f-4ab1-9deb-e1ed12ca059d', 'GT World Challenge Europe', 'gtworldchallengeeurope');


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.countries (id, code) VALUES ('ce2cd790-5d26-41ff-99a8-82abba8b49e6', 'qa');
INSERT INTO public.countries (id, code) VALUES ('d2ae1e36-b0db-41d5-b86e-7cb68f571ee4', 'my');
INSERT INTO public.countries (id, code) VALUES ('a74fcc5a-0f02-4cc9-96ad-5154fc6db59b', 'pt');
INSERT INTO public.countries (id, code) VALUES ('63c9b1ff-5b8e-4cf5-8438-e0e212bde90c', 'ar');
INSERT INTO public.countries (id, code) VALUES ('3752ca78-3d54-4c61-b9e3-2eb78bb7f85e', 'kz');
INSERT INTO public.countries (id, code) VALUES ('8169965d-7424-4a15-b30d-5cdb3f3f4f32', 'in');
INSERT INTO public.countries (id, code) VALUES ('54427f06-1b66-41fa-bf0f-7b96e9026d2e', 'id');
INSERT INTO public.countries (id, code) VALUES ('970b3cec-a230-45e3-8b41-b4ec1e01a344', 'za');
INSERT INTO public.countries (id, code) VALUES ('2930a4a8-a360-464d-8e4a-5ff37d75f62c', 'kr');


--
-- Data for Name: seasons; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

INSERT INTO public.seasons (id, championship, year) VALUES ('28137908-06e4-4346-b309-c4c04dda4e10', 'f4618d91-2df9-4a39-b857-7b751b27111a', 2022);
INSERT INTO public.seasons (id, championship, year) VALUES ('50093d15-7156-4cee-874c-97e531511dc6', 'f4618d91-2df9-4a39-b857-7b751b27111a', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('f9a7174d-cef8-4436-ad19-c32edbf33673', 'e64a940b-e4c3-4caa-948a-dca4b9716c78', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('9ef0243a-6f56-40d7-8249-7ed3d1f28901', '38afa966-116c-4ec1-ae20-590c43977906', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '24b1d23b-d02b-41f2-82e3-62e73b105691', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', '647fcf89-b1d3-4450-997c-884313c1b259', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('74cc992a-c0bd-4970-9e94-4cffcd0dc434', 'e5ae8a14-3fa8-4adf-95af-a4f2dd97ff42', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('e1bf83c0-80f1-4869-b0c0-1d9cee7aa264', '05b1d906-3a15-4a88-a623-0e10b2c2e88c', 2023);
INSERT INTO public.seasons (id, championship, year) VALUES ('50a8ab1a-6b74-45a0-85d5-49678a07d9ca', 'bc702072-ce7f-4ab1-9deb-e1ed12ca059d', 2023);


--
-- Data for Name: teams; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
-- Data for Name: cars; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.cars (id, number, team, season) VALUES ('eb970384-3630-4620-87b3-bdf0f2f4384c', 24, '433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('8c481614-90d4-4178-928f-591294d2caef', 77, '433ccb42-fe09-4ba1-a4b0-e6f5c0c7850d', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('a490d12e-2720-429f-8c88-fbb63faccc2c', 21, '287c8e13-af66-42da-8df4-2e79560afb2c', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('c064d0a4-4392-4b9e-9f3d-1684f542edb9', 22, '287c8e13-af66-42da-8df4-2e79560afb2c', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('47ff7165-fb78-4672-88f3-a476463ecd7e', 10, '411baf4f-a7f5-465c-8acd-d4d649b4eaff', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('ec7726a2-8144-40c4-a9a8-510a4e48fcb8', 31, '411baf4f-a7f5-465c-8acd-d4d649b4eaff', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('701d5d67-322a-4698-807e-790dc36ef2c9', 14, '91793127-a828-4bac-870e-b781f94a8bc8', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('6f18d71b-2d3c-452a-be0e-2851bc4df46e', 18, '91793127-a828-4bac-870e-b781f94a8bc8', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('4d80e144-7260-4c17-a36b-842a59ddd3c7', 16, '664150ac-aeb0-4e06-a35c-c4a6c102cb70', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('c58d852c-0475-48fa-8064-b078b5ef92ba', 55, '664150ac-aeb0-4e06-a35c-c4a6c102cb70', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('cc7a3100-8dae-40e1-bae2-119fa42c2901', 20, '4575090f-e9e7-4a70-802b-f8cdc94f1cd3', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('33ad85b8-1c68-4cbf-8a88-0c10ed6cbf83', 27, '4575090f-e9e7-4a70-802b-f8cdc94f1cd3', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('db0a5c33-5af5-42af-8b9a-c6470e9d085d', 4, '9d219474-9719-471e-b2f7-0ccc0f666754', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('e0b6d951-9f75-4d7d-9f7a-c5dc48a796c8', 81, '9d219474-9719-471e-b2f7-0ccc0f666754', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('db82c1a8-df57-4573-ba00-c053b0dba526', 44, 'a050a229-f93a-49f8-9209-8ce4297f6b26', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('af083867-6b05-4dc2-8ed0-25464f0232ec', 63, 'a050a229-f93a-49f8-9209-8ce4297f6b26', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('0f4b7fc5-c714-41d1-b860-7aaf226057cf', 1, '0521d82c-77a7-4d46-81f6-ab3741c3e554', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('d45c62d1-de1e-441e-bfc5-9349ba814465', 11, '0521d82c-77a7-4d46-81f6-ab3741c3e554', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('56073ab5-f373-478f-a041-d734e0af0f0f', 2, '08903254-d543-482e-a358-e6129a3f4ffe', '50093d15-7156-4cee-874c-97e531511dc6');
INSERT INTO public.cars (id, number, team, season) VALUES ('86bd6f56-be33-411b-bafc-d2254eb67a27', 23, '08903254-d543-482e-a358-e6129a3f4ffe', '50093d15-7156-4cee-874c-97e531511dc6');


--
-- Data for Name: championship_presentations; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('8f0777bb-e267-42e8-a882-04c5d1b5e0aa', '24b1d23b-d02b-41f2-82e3-62e73b105691', 'imsa.svg', '#fefffe', '2023-02-20 19:35:52');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('19f8b660-51b2-41ca-be05-9691b7a05e52', '647fcf89-b1d3-4450-997c-884313c1b259', 'fe.svg', '#019dcb', '2023-02-20 19:36:07');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('45032318-2b17-46c2-b79b-af1c334708fd', '05b1d906-3a15-4a88-a623-0e10b2c2e88c', 'gt-world-challenge.svg', '#e04b00', '2023-02-20 19:36:43');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('099b1713-3cce-4fed-8a41-f405b8bee329', 'bc702072-ce7f-4ab1-9deb-e1ed12ca059d', 'gt-world-challenge-europe.svg', '#e09600', '2023-02-20 19:37:00');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('99b574f3-4a28-46d2-a32a-c8b2e873562f', 'f4618d91-2df9-4a39-b857-7b751b27111a', 'f1.svg', '#e00000', '2023-02-18 18:47:12');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('20a155bb-ed52-4fd4-be44-0cdb4af4b7a7', 'e64a940b-e4c3-4caa-948a-dca4b9716c78', 'motogp.svg', '#e0e000', '2023-02-20 16:25:03');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('a80fe78b-d3c3-4799-a7fa-b421fd673a18', '38afa966-116c-4ec1-ae20-590c43977906', 'wec.svg', '#0649a1', '2023-02-20 17:42:19');
INSERT INTO public.championship_presentations (id, championship, icon, color, created_on) VALUES ('f83829cc-96b3-4357-80f9-261bde68e86e', 'e5ae8a14-3fa8-4adf-95af-a4f2dd97ff42', 'f2.svg', '#043961', '2023-02-20 19:36:20');


--
-- Data for Name: drivers; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('1dfe05c7-7693-4a94-9333-54ebd485916a', 'Piastri', 'Oscar', 'b94997db-940a-4ddc-a133-a4140d168b94');
INSERT INTO public.drivers (id, name, firstname, country) VALUES ('c0b2c74e-762e-4b0e-8875-cbaf7fab4717', 'Sargeant', 'Logan', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');


--
-- Data for Name: driver_moves; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('2f92f4b4-2bd6-4bd2-a178-893db680d104', '368de2ea-cc01-412e-a670-65f3512daf10', 'eb970384-3630-4620-87b3-bdf0f2f4384c', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('b3ab0b0f-2b58-4016-93c5-583d636b34ed', '93cd5944-2646-4681-ba0c-fe24be31a2d7', '8c481614-90d4-4178-928f-591294d2caef', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('cc04e48c-eee1-4f11-9152-e4534bef0836', 'f3296964-0bf7-4ad6-aa47-3fc349d66720', 'a490d12e-2720-429f-8c88-fbb63faccc2c', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('d2502f92-4b5e-4ea2-bff8-cfe3eb290e76', '4da2cb59-5bc4-4502-983c-8ab5ba156f78', 'c064d0a4-4392-4b9e-9f3d-1684f542edb9', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('5c02960d-d73f-4909-92e9-93d5a5224f30', 'd98aaa41-6948-4216-9585-c84faedcd17c', '47ff7165-fb78-4672-88f3-a476463ecd7e', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('52b35b8b-d194-4fc2-994a-efbb5736fa75', 'b8242bfc-831e-41d3-99ca-a15906bef28b', 'ec7726a2-8144-40c4-a9a8-510a4e48fcb8', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('e42dc988-1dee-4f20-b55a-aaa5d2d34355', '0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96', '701d5d67-322a-4698-807e-790dc36ef2c9', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('7a43e40e-3eb4-43ee-a984-b785a2530f74', 'b480ff53-a91e-49bb-8de2-ba673501982f', '6f18d71b-2d3c-452a-be0e-2851bc4df46e', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('0241b657-b8c6-4557-a217-c4315a1ee50f', '886072d0-3f71-44d9-a28e-5d1120b34239', '4d80e144-7260-4c17-a36b-842a59ddd3c7', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('946efa8e-56e8-4dea-b0a5-4d2d08049642', '5db217dc-d298-479e-ac24-0e2e0178d30a', 'c58d852c-0475-48fa-8064-b078b5ef92ba', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('2c2a9200-ff38-4e36-9d2a-b27adeb0252c', 'caf2d7ff-8372-4043-b79d-e2062d799da6', 'cc7a3100-8dae-40e1-bae2-119fa42c2901', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('bff18e66-62ba-46a3-a314-abff7f63e852', '88038686-7a0e-4d23-8043-93e56e48a804', '33ad85b8-1c68-4cbf-8a88-0c10ed6cbf83', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('fcb1d05f-52c0-469d-9b0b-159915586803', 'f4c60528-4f64-47d0-b460-15c0ec4d69fe', 'db0a5c33-5af5-42af-8b9a-c6470e9d085d', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('0fde5fd7-da20-49bb-9e7c-8501161028e1', '1dfe05c7-7693-4a94-9333-54ebd485916a', 'e0b6d951-9f75-4d7d-9f7a-c5dc48a796c8', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('3ff0710e-1a96-427f-a2b9-2b2b5b85137c', '72dbaa68-4dc3-4087-90b0-8153cb441738', 'db82c1a8-df57-4573-ba00-c053b0dba526', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('b8da1b1a-4912-4c55-8370-dd3d7b871d6d', '494f6b1d-7622-4032-bf04-182a0dee404e', 'af083867-6b05-4dc2-8ed0-25464f0232ec', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('575abfb7-2158-453b-b470-569d65e4ccaa', '376f88ca-b07a-4802-b117-799421598d03', '0f4b7fc5-c714-41d1-b860-7aaf226057cf', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('a538b3e6-5b2d-42fc-8139-e27ba182275e', '59dc6702-fde8-4b2f-b04e-1a0f8ffebe38', 'd45c62d1-de1e-441e-bfc5-9349ba814465', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('1b9daef2-53a4-4fd3-8d74-c355d647ef25', 'c0b2c74e-762e-4b0e-8875-cbaf7fab4717', '56073ab5-f373-478f-a041-d734e0af0f0f', '2023-01-01 00:00:00');
INSERT INTO public.driver_moves (id, driver, car, date) VALUES ('ccf4fbe2-8ca6-4e4c-9a10-3bbe98197c6f', '043f15b7-7e92-4a7e-8e2d-08db6ae3c283', '86bd6f56-be33-411b-bafc-d2254eb67a27', '2023-01-01 00:00:00');


--
-- Data for Name: venues; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.venues (id, name, country) VALUES ('5a7e687f-8943-418d-b2c2-5ba3baaf7a76', 'Lusail International Circuit', 'ce2cd790-5d26-41ff-99a8-82abba8b49e6');
INSERT INTO public.venues (id, name, country) VALUES ('4a9094cd-fadd-47c9-86be-7cb7a8f9e89e', 'Las Vegas', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('5b264ab4-3d90-4fdd-8c92-f27c13dc24f2', 'Sepang International Circuit', 'd2ae1e36-b0db-41d5-b86e-7cb68f571ee4');
INSERT INTO public.venues (id, name, country) VALUES ('416fea55-da32-4aa7-b99c-0844c0239f56', 'Autódromo Internacional do Algarve', 'a74fcc5a-0f02-4cc9-96ad-5154fc6db59b');
INSERT INTO public.venues (id, name, country) VALUES ('3a6992e6-0d08-4ba4-afc4-48c5f2f16bfb', 'Termas de Río Hondo', '63c9b1ff-5b8e-4cf5-8438-e0e212bde90c');
INSERT INTO public.venues (id, name, country) VALUES ('d349a287-e5c7-41a1-b4e7-1aff3fb028a5', 'Circuito de Jerez - Angel Nieto', '0a7c4ba4-b3a0-4219-a5bd-6466750441a5');
INSERT INTO public.venues (id, name, country) VALUES ('78ee67cd-4f41-42ca-8f27-0c024e9be263', 'Le Mans', '606e0956-7707-43b4-a065-57f59a6b2885');
INSERT INTO public.venues (id, name, country) VALUES ('7007325a-86e7-42c6-b6e7-603d772c9067', 'Autodromo Internazionale del Mugello', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.venues (id, name, country) VALUES ('1838df8d-3a57-4372-b30c-98269cade109', 'Sachesenring', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.venues (id, name, country) VALUES ('9ca7b716-f738-4ae1-bc15-992ff7c384b8', 'TT Circuit Assen', 'b16cab89-6df3-4e96-8858-1ef550e6c946');
INSERT INTO public.venues (id, name, country) VALUES ('f4906313-1989-4b47-ae89-44e5f4adcdb5', 'Sokol International Racetrack', '3752ca78-3d54-4c61-b9e3-2eb78bb7f85e');
INSERT INTO public.venues (id, name, country) VALUES ('8e6b6661-b1eb-4a89-98f1-05e4f586cba4', 'Misano World Circuit Marco Simoncelli', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.venues (id, name, country) VALUES ('66b87ffb-0abf-432d-adf9-8a87a6b6ccf4', 'Buddh Internacional Circuit', '8169965d-7424-4a15-b30d-5cdb3f3f4f32');
INSERT INTO public.venues (id, name, country) VALUES ('3c7356e3-3605-48ea-9c0c-384f788f0e65', 'Mobility Resort Motegi', '29e86eef-3917-4352-afbb-9407f33ec479');
INSERT INTO public.venues (id, name, country) VALUES ('2578791d-c57c-4877-ab52-ce869329c625', 'Mandalika International Street Circuit', '54427f06-1b66-41fa-bf0f-7b96e9026d2e');
INSERT INTO public.venues (id, name, country) VALUES ('e0c5467e-2837-463e-8a17-8fb1f49b0255', 'Phillip Island', 'b94997db-940a-4ddc-a133-a4140d168b94');
INSERT INTO public.venues (id, name, country) VALUES ('b8c08866-437f-46bb-a069-475d02778ef8', 'Chang International Circuit', '2546e6e8-2d5f-4730-8f3d-7346c34991b7');
INSERT INTO public.venues (id, name, country) VALUES ('d2ddd638-bdc0-4501-b6be-f9d30749c89b', 'Sebring International Raceway', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('018faf97-1e9a-4bd0-8929-4157692158da', 'Fuji Speedway', '29e86eef-3917-4352-afbb-9407f33ec479');
INSERT INTO public.venues (id, name, country) VALUES ('955c0c3f-0f45-40e7-adff-13ed23c00d72', 'Daytona International Speedway', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('2cd6953c-35f9-4a7f-802c-42abf11f03f1', 'Long Beach Street Circuit', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('f86a312a-9346-456b-b951-bfd2a2c33e59', 'WeatherTech Raceway Laguna Seca', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('77dc8569-1523-4dd7-ab2a-c77519fb8570', 'Watkins Glen International', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('7f7715ad-eb38-4b87-8ba1-e7957aeb1caa', 'Canadian Tire Motorsport Park', '7f49f09b-f1f7-4312-b5d0-e8be78e10f01');
INSERT INTO public.venues (id, name, country) VALUES ('c987fbd7-e1b7-47a0-b7bf-78661c20591f', 'Lime Rock Park', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('581059c6-97fb-4b09-9a86-9f0c979253f1', 'Road America', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('bb862ede-5353-4bad-8f2b-f2fd87ea85dd', 'VIRginia International Raceway', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('760e5f01-9c4e-426d-b998-f1b4b26a5349', 'Indianapolis Motor Speedway', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('6e6c0e03-4fb0-4dbc-9b0d-4c0d58101008', 'Michelin Raceway Road Atlanta', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('0b4934dc-0a43-4168-b4ef-e5fa05e2ff30', 'Circuit Ricardo Tormo Valencia', '0a7c4ba4-b3a0-4219-a5bd-6466750441a5');
INSERT INTO public.venues (id, name, country) VALUES ('6ccc70ff-058f-44de-8a88-785b0f4eeaee', 'Brands Hatch Circuit Kent', '849c2feb-208f-40f7-b719-131e34082381');
INSERT INTO public.venues (id, name, country) VALUES ('78260cfc-5cc9-418e-845f-caf31ef3e5f9', 'Hockenheimring', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.venues (id, name, country) VALUES ('d4ad8683-8cf2-4c96-bd4d-6b3cf7f065a7', 'Nürburgring', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.venues (id, name, country) VALUES ('e6425c22-46d6-452f-9ce6-98a9bdfdc2bd', 'Mexico City', '98d0e3cd-d803-4517-9cdb-7feb82b0422b');
INSERT INTO public.venues (id, name, country) VALUES ('fb00633f-3da4-4f29-ad8d-720831e96e8f', 'City of Diriyah', '237e1373-7a68-4841-b541-d17f340aa003');
INSERT INTO public.venues (id, name, country) VALUES ('30fb9fdc-0011-43a7-afbd-1419808a3404', 'City of Hyderabad', '8169965d-7424-4a15-b30d-5cdb3f3f4f32');
INSERT INTO public.venues (id, name, country) VALUES ('f6176e7f-4fca-4d63-9f5a-f51767f59d03', 'City of Cape Town', '970b3cec-a230-45e3-8b41-b4ec1e01a344');
INSERT INTO public.venues (id, name, country) VALUES ('b2f01e76-6452-444a-9dda-b0f672c73d10', 'City of Sao Paulo', '9157eea3-8a50-4a8c-a139-8310731c0d65');
INSERT INTO public.venues (id, name, country) VALUES ('0ac89de2-194c-49f9-baa4-f277c46058f1', 'City of Berlin', 'c1400d2e-b38f-4562-a019-2f2d82f37d01');
INSERT INTO public.venues (id, name, country) VALUES ('fc694ed7-a6c3-4ec1-80c2-f5799f978add', 'City of Seoul', '2930a4a8-a360-464d-8e4a-5ff37d75f62c');
INSERT INTO public.venues (id, name, country) VALUES ('c2c97bc1-402e-47fc-9a18-826abacdd091', 'City of Jakarta', '54427f06-1b66-41fa-bf0f-7b96e9026d2e');
INSERT INTO public.venues (id, name, country) VALUES ('cf6b6fdb-0285-4f51-85d5-d835c4fe4063', 'City of Portland', '73d7cf5c-6add-46cb-a97a-4757c6b7764c');
INSERT INTO public.venues (id, name, country) VALUES ('c2571251-7af3-4d77-89c6-91bcf0fa2d40', 'City of Rome', '87afcfd4-a08c-47bf-8d96-e903b34f6c39');
INSERT INTO public.venues (id, name, country) VALUES ('a77d06c6-a381-430b-a420-22166956d24b', 'City of London', '849c2feb-208f-40f7-b719-131e34082381');


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.events (id, season, venue, index, label) VALUES ('91fd7aca-83ab-456f-9142-53697c355c76', '50093d15-7156-4cee-874c-97e531511dc6', '409bb6eb-aaaf-48b9-9012-07bc9628ffe2', 0, 'Aramco Pre-Season Testing');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('ad730445-6797-444c-9232-4f8c531a49a1', '50093d15-7156-4cee-874c-97e531511dc6', '409bb6eb-aaaf-48b9-9012-07bc9628ffe2', 1, 'Gulf Bahrain GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('4c5b906c-7485-4e48-aaf0-538aec307bf8', '50093d15-7156-4cee-874c-97e531511dc6', 'e400d424-2bee-47ab-a1f3-637823129f88', 2, 'STC Saudi Arabian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2a8051a2-0575-4618-b90b-6bd45f6730db', '50093d15-7156-4cee-874c-97e531511dc6', 'a9cbe6b1-e38f-417c-b206-824fc7ae1f7c', 3, 'Rolex Australian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('78c22dd9-be1e-4945-960c-01658201044d', '50093d15-7156-4cee-874c-97e531511dc6', '13d8a8ce-074f-487c-b514-fd109f62934b', 4, 'Azerbaijan GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('954f773a-dde5-4636-b766-0854419872bb', '50093d15-7156-4cee-874c-97e531511dc6', 'c2116751-22ca-40d1-bf2f-2fd30ab0cd18', 5, 'Crypto.com Miami GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('7681d69c-031d-4dd5-b0e7-297d2d1bfda9', '50093d15-7156-4cee-874c-97e531511dc6', 'ef313471-20cc-42c3-91eb-9d079a7f7b03', 6, 'Emilia Romagna GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('09318e72-4327-4e63-8e9d-1b5f3d99fa4d', '50093d15-7156-4cee-874c-97e531511dc6', '7242a078-f76c-4166-8658-70eb8fc909a0', 7, 'Grand Prix de Monaco');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('485fb859-40e6-452f-bb39-e08ad61b91d9', '50093d15-7156-4cee-874c-97e531511dc6', 'd2f04839-e8de-484a-98f7-beda803d4239', 8, 'AWS Gran Premio de España');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('c921f7b9-d477-479f-8aa0-2a476624ee12', '50093d15-7156-4cee-874c-97e531511dc6', '89c1ba61-c777-461e-9abc-c524c34a3b68', 9, 'Pirelli Grand Prix du Canada');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('885e9dd1-5abe-428e-8019-f603ceae0685', '50093d15-7156-4cee-874c-97e531511dc6', '0455078c-93a1-41c1-ac3a-6149908b04fa', 10, 'Grosser Preis Von Österreich');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('13ac76b8-a636-4914-b76b-e84a939424cf', '50093d15-7156-4cee-874c-97e531511dc6', '0965e59b-fe6f-4799-8239-01f95d665ef4', 11, 'Aramco British GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('c96020a3-16f1-40cf-9bb5-ae4c34ca2dfa', '50093d15-7156-4cee-874c-97e531511dc6', '90cf17ad-29f6-43c1-999a-84674b0c758f', 12, 'Hungarian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('35246f2b-ec9a-43dc-aa33-d92250fde07f', '50093d15-7156-4cee-874c-97e531511dc6', 'c85b066e-72fe-41e9-b787-3cb4c6ca5de9', 13, 'Belgian GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('4fafd909-d7c4-4a29-b0be-b4d46a6266f4', '50093d15-7156-4cee-874c-97e531511dc6', '6d6cf63e-751d-4153-bff4-354ab70951fb', 14, 'Heineken Dutch GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('0e3e5120-29e9-48cb-bd93-abb75c03acdc', '50093d15-7156-4cee-874c-97e531511dc6', '9d5146df-6054-4238-bb63-897d1f4dcc2b', 15, 'Pirelli Gran Premio D''Italia');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('68dc4465-976b-4b05-85b3-a726f0938b4a', '50093d15-7156-4cee-874c-97e531511dc6', 'bf8dc1d2-760f-468f-93ad-046230923184', 17, 'Lenovo Japanese GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('dac38989-9a52-4812-87d9-e7074a4484e4', '50093d15-7156-4cee-874c-97e531511dc6', '5a7e687f-8943-418d-b2c2-5ba3baaf7a76', 18, 'Qatar GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('688a4832-32f7-47b6-b45b-2e7b9f6a5f19', '50093d15-7156-4cee-874c-97e531511dc6', '71ed29b0-1ec8-4402-8d85-65e1ed6d7eef', 19, 'Lenovo United States GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('58882396-4113-4643-96ec-4538b194a38c', '50093d15-7156-4cee-874c-97e531511dc6', '415d0c44-0804-445c-9579-56fe5e976dd5', 20, 'Gran Premio de la Ciudad de México');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('3bf31a09-736d-4320-8032-e4bf48eaa266', '50093d15-7156-4cee-874c-97e531511dc6', '4a9094cd-fadd-47c9-86be-7cb7a8f9e89e', 22, 'Heineken Silver Las Vegas GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('4f00b004-429c-4900-8b00-3e47a9760279', '50093d15-7156-4cee-874c-97e531511dc6', 'bd9bf718-17d4-4f9b-b3ee-5f95b6155611', 16, 'Singapore Airlines Singapore GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('c1c9a8e7-ef72-4a41-b483-91242633534b', '50093d15-7156-4cee-874c-97e531511dc6', '508affbb-0506-4423-9fe3-725b49d95274', 23, 'Etihad Airways Abu Dhabi GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('e1463a2d-b2e5-4b37-b400-e7af50bcfad5', '50093d15-7156-4cee-874c-97e531511dc6', '9cc0ccc4-504c-423d-a16e-24c951d36a4b', 21, 'Rolex Grande Prêmio de São Paulo');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('0fd11778-2cea-4da8-b2a8-69c6e953db4b', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '5b264ab4-3d90-4fdd-8c92-f27c13dc24f2', 0, 'Sepang MotoGP Official Test');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('bf81cdb9-3918-4878-94de-39387b56c160', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '416fea55-da32-4aa7-b99c-0844c0239f56', 1, 'Portimao MotoGP Official Test');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('06818f75-91cc-4076-8ffd-0a25b8576e69', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '416fea55-da32-4aa7-b99c-0844c0239f56', 2, 'GP de Portugal');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('9039a428-128a-4bbe-9973-2ab76ea06d27', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '3a6992e6-0d08-4ba4-afc4-48c5f2f16bfb', 3, 'GP Michelin de la República Argentina');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('9da2f180-99b3-4d52-a39d-0bee47bd47f1', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '71ed29b0-1ec8-4402-8d85-65e1ed6d7eef', 4, 'Red Bull GP of The Americas');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('e73b1f3a-420a-4ea8-9a99-e8f13095746b', 'f9a7174d-cef8-4436-ad19-c32edbf33673', 'd349a287-e5c7-41a1-b4e7-1aff3fb028a5', 5, 'GP de España');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('4eadaf5b-710c-4a67-811f-9369fd8daf4f', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '78ee67cd-4f41-42ca-8f27-0c024e9be263', 6, 'Shark GP de France');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('7bf959f6-077e-4f14-9d4c-0615e2e49daa', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '7007325a-86e7-42c6-b6e7-603d772c9067', 7, 'GP d''Italia Oakley');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('f213fe1b-bf87-422c-a9f8-ed265ef888e3', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '1838df8d-3a57-4372-b30c-98269cade109', 8, 'Liqui moly MGP Deutschland');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('09d53848-e46d-4725-a43a-fa8565266339', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '9ca7b716-f738-4ae1-bc15-992ff7c384b8', 9, 'Motul TT Assen');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('17f98797-e35b-45dc-81f0-2adb24548930', 'f9a7174d-cef8-4436-ad19-c32edbf33673', 'f4906313-1989-4b47-ae89-44e5f4adcdb5', 10, 'GP of Kazakhstan');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('deb34f29-09ea-4444-84e1-02659cbd898d', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '0965e59b-fe6f-4799-8239-01f95d665ef4', 11, 'Monster Energy British GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('7dc23ce0-288d-4b14-ab3c-8c14924445ae', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '0455078c-93a1-41c1-ac3a-6149908b04fa', 12, 'CrytoDATA MGP von Österreich');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('20a81e5d-4c41-4168-82f8-f59634648b8b', 'f9a7174d-cef8-4436-ad19-c32edbf33673', 'd2f04839-e8de-484a-98f7-beda803d4239', 13, 'GP Monster Energy de Catalunya');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('1eb2eba8-5ecc-4ebc-b0b8-dbb21d2725d9', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '8e6b6661-b1eb-4a89-98f1-05e4f586cba4', 14, 'GP di San Marino e della Riviera di Rimini');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('9ce570e7-da1b-449c-8c19-39da3a41211a', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '66b87ffb-0abf-432d-adf9-8a87a6b6ccf4', 15, 'GP of India');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('69522e63-ef6a-4a60-a0f3-3d59a46f26b5', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '3c7356e3-3605-48ea-9c0c-384f788f0e65', 16, 'MGP of Japan');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('f46886fd-b2da-4e87-9a72-b2b3cac22f73', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '2578791d-c57c-4877-ab52-ce869329c625', 17, 'Pertamina GP of Indonesia');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('4dbe7d13-5c25-4dd2-9246-fd56d2fa4e5d', 'f9a7174d-cef8-4436-ad19-c32edbf33673', 'e0c5467e-2837-463e-8a17-8fb1f49b0255', 18, 'Animoca Brands Australian MGP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('3d85572c-eb4a-460f-96ec-b9cb2f996226', 'f9a7174d-cef8-4436-ad19-c32edbf33673', 'b8c08866-437f-46bb-a069-475d02778ef8', 19, 'OR Thailand GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2b9b022e-3571-4bb3-bad4-b5f3417e44d8', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '5b264ab4-3d90-4fdd-8c92-f27c13dc24f2', 20, 'Petronas GP of Malaysia');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('900927bf-e651-48b9-b202-943396e5fe5a', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '5a7e687f-8943-418d-b2c2-5ba3baaf7a76', 21, 'GP of Qatar');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('3b17d019-e1a4-40c2-a7cf-9de47261f5d5', 'f9a7174d-cef8-4436-ad19-c32edbf33673', '0b4934dc-0a43-4168-b4ef-e5fa05e2ff30', 22, 'GPM de la Comunitat Valenciana');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2972f677-aa6d-4f99-bdc0-4b1685f34bf6', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', 'd2ddd638-bdc0-4501-b6be-f9d30749c89b', 0, 'R1 Sebring | 1000 Miles');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('0889b10a-a652-4965-a3ea-757de0d2ccb1', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', '416fea55-da32-4aa7-b99c-0844c0239f56', 1, 'R2 Portimao | 6H');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('1cf287d9-1532-4cd1-9651-d4dc7fcb446c', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', 'c85b066e-72fe-41e9-b787-3cb4c6ca5de9', 2, 'R3 Spa-Francorchamps | 6H');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('01e3829e-5017-4af7-9d73-509485417eb0', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', '78ee67cd-4f41-42ca-8f27-0c024e9be263', 3, 'R4 Le Mans | 24H');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('94a14683-4f80-4c85-bfa9-ae2e638262ac', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', '9d5146df-6054-4238-bb63-897d1f4dcc2b', 4, 'R5 Monza | 6H');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2df96fc9-2d08-4a7b-a973-4e6a78e67e14', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', '018faf97-1e9a-4bd0-8929-4157692158da', 5, 'R6 Fuji | 6H');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('93897fce-b1c0-4fed-8411-afeddd483937', '9ef0243a-6f56-40d7-8249-7ed3d1f28901', '409bb6eb-aaaf-48b9-9012-07bc9628ffe2', 6, 'R7 Bahrain | 8H');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('9e4f0f2e-750a-49c6-8b1f-0ad46a728371', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '955c0c3f-0f45-40e7-adff-13ed23c00d72', 0, 'Rolex 24 at Daytona');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('ac547b54-2f7e-41d0-a944-50ffdfe658f2', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', 'd2ddd638-bdc0-4501-b6be-f9d30749c89b', 1, '12 Hours of Sebring');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('dcbf25ee-1b60-48fc-a103-45ed9aa3f0b6', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '2cd6953c-35f9-4a7f-802c-42abf11f03f1', 2, 'Acura GP of Long Beach');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('7dae93a2-26c7-49f7-9eab-09b5942bcca0', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', 'f86a312a-9346-456b-b951-bfd2a2c33e59', 3, 'Motul Course de Monterey');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('d6c1da4b-a41f-4955-a065-381a80645b1c', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '77dc8569-1523-4dd7-ab2a-c77519fb8570', 4, '6 Hours of The Glen');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('efd97afb-2461-4a64-b273-b1a695859781', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '7f7715ad-eb38-4b87-8ba1-e7957aeb1caa', 5, 'Chevrolet GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('e43f86a8-e125-4e9b-88f0-58eb6aa0d9e8', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', 'c987fbd7-e1b7-47a0-b7bf-78661c20591f', 6, 'FCP Euro Northeast GP');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('068e7ec8-8f12-44dc-82eb-f88737ee461e', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '581059c6-97fb-4b09-9a86-9f0c979253f1', 7, 'IMSA Sportscar Weekend');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('217131e2-173c-4dd0-87af-eb75bf7ba0bf', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', 'bb862ede-5353-4bad-8f2b-f2fd87ea85dd', 8, 'Michelin GT Challenge at VIR');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2261119c-6781-412c-9e94-34a869b2ea64', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '760e5f01-9c4e-426d-b998-f1b4b26a5349', 9, 'IMSA Battle on The Bricks');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('7064a92b-ec41-4b89-ac9e-de4b066869c7', '12a4951f-9dfa-47ec-8f5e-73c8aeb3406a', '6e6c0e03-4fb0-4dbc-9b0d-4c0d58101008', 10, 'Motul Petit Le Mans');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('25ab3fdb-d2fd-4a99-a83a-2351c276a0cc', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '9d5146df-6054-4238-bb63-897d1f4dcc2b', 1, 'R1 Monza Endurance');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('25bdb04c-6648-423f-9399-8e8b2dea0d3b', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '6ccc70ff-058f-44de-8a88-785b0f4eeaee', 2, 'R2 Brands Hatch Sprint');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('48f6cf8f-f088-41fc-8f1d-324adfefc522', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '05c6b668-ef04-4412-9a1f-46cf8c1aabbf', 3, 'R3 Endurance Paul Ricard');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('013bf375-bcd3-4b19-b2d0-85541b322b02', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', 'c85b066e-72fe-41e9-b787-3cb4c6ca5de9', 4, 'R4 CrowdStrike 24 Hours of Spa');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('4c663866-cb66-4072-89b6-c855668c315f', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '8e6b6661-b1eb-4a89-98f1-05e4f586cba4', 5, 'R5 Sprint Misano');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('b958b6f5-f905-4fae-97ba-f6ad1e345bf5', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', 'd4ad8683-8cf2-4c96-bd4d-6b3cf7f065a7', 6, 'R6 Endurance Nürburgring');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('fe01c86d-561d-4bd1-ad58-a33b8e97a747', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '78260cfc-5cc9-418e-845f-caf31ef3e5f9', 7, 'R7 Sprint Hockenheim');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('95ed073c-20a7-4bdb-9e46-a1a6a5e9450d', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '0b4934dc-0a43-4168-b4ef-e5fa05e2ff30', 8, 'R8 Sprint Ricardo Tormo Valencia');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('5e4d9d8d-95ba-46f1-96b3-3b57acc581fe', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', 'd2f04839-e8de-484a-98f7-beda803d4239', 9, 'R9 Endurance Barcelona');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('77076e82-22f9-4cc0-be96-dc9bb2bd6c2c', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '6d6cf63e-751d-4153-bff4-354ab70951fb', 10, 'R10 Sprint Zandvoort');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('2a133fdf-4b7d-4b9a-9b03-27457896051d', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '409bb6eb-aaaf-48b9-9012-07bc9628ffe2', 0, 'R1 Sakhir');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('a7251c75-c48a-4ce2-8c86-8a4a5625c06c', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', 'e400d424-2bee-47ab-a1f3-637823129f88', 1, 'R2 Jeddah');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('fd6da8d7-c86b-4b01-8ea0-90c6265a82c2', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', 'a9cbe6b1-e38f-417c-b206-824fc7ae1f7c', 2, 'R3 Melbourne');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('8758408b-8979-4306-9ffc-b5e87184f91d', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '13d8a8ce-074f-487c-b514-fd109f62934b', 3, 'R4 Baku');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('de100482-1595-43cd-9d9b-5fce2229219c', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', 'ef313471-20cc-42c3-91eb-9d079a7f7b03', 4, 'R5 Imola');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('e19bb729-9f1e-4d34-bb15-f5675bfd43b1', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '7242a078-f76c-4166-8658-70eb8fc909a0', 5, 'R6 Monte Carlo');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('736547a1-4baf-4121-953c-c6b082490117', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', 'd2f04839-e8de-484a-98f7-beda803d4239', 6, 'R7 Barcelona');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('6f3333eb-eeb4-47f0-a967-438b322c9f79', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '0455078c-93a1-41c1-ac3a-6149908b04fa', 7, 'R8 Spielberg');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('789a2650-5dae-4d41-8b23-b4f8756347c6', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '0965e59b-fe6f-4799-8239-01f95d665ef4', 8, 'R9 Silverstone');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('ee3ae2af-601b-426a-b8e5-8f652280462f', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '90cf17ad-29f6-43c1-999a-84674b0c758f', 9, 'R10 Budapest');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('551a5e3d-7830-4576-b058-076eb1790765', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', 'c85b066e-72fe-41e9-b787-3cb4c6ca5de9', 10, 'R11 Spa-Francorchamps');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('c1f6bc2c-8c35-4b3b-a4fc-876697eaed4b', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '6d6cf63e-751d-4153-bff4-354ab70951fb', 11, 'R12 Zandvoort');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('920c1719-187d-4f79-9244-52c86046b76f', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '9d5146df-6054-4238-bb63-897d1f4dcc2b', 12, 'R13 Monza');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('151e5a6d-be04-4794-ae56-e27b214adac3', '74cc992a-c0bd-4970-9e94-4cffcd0dc434', '508affbb-0506-4423-9fe3-725b49d95274', 13, 'R14 Yas Island');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('6119ab77-2b69-45d7-978c-e5684a6e4eaf', '50a8ab1a-6b74-45a0-85d5-49678a07d9ca', '05c6b668-ef04-4412-9a1f-46cf8c1aabbf', 0, 'Paul Ricard Test Days');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('e645eab3-b510-43a1-9f18-0b5188b9e131', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'e6425c22-46d6-452f-9ce6-98a9bdfdc2bd', 0, 'Mexico City EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('d49a877f-1937-43f2-85d3-f035751f350e', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'fb00633f-3da4-4f29-ad8d-720831e96e8f', 1, 'Diriyah EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('59ec6f9f-6ad9-4e76-883a-e14a1bf76fb5', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', '30fb9fdc-0011-43a7-afbd-1419808a3404', 2, 'Hyderabad EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('bc005609-a1c2-4825-9239-52601a5346ca', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'f6176e7f-4fca-4d63-9f5a-f51767f59d03', 3, 'Cape Town EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('be4c80e7-24d2-4ee1-ac05-7e62c83e8542', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'b2f01e76-6452-444a-9dda-b0f672c73d10', 4, 'Sao Paulo EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('b4eadc1f-3546-4464-a254-0c295b1c0781', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', '0ac89de2-194c-49f9-baa4-f277c46058f1', 5, 'Berlin EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('46e44679-cbad-4c0a-8ec1-d858483f6672', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', '7242a078-f76c-4166-8658-70eb8fc909a0', 6, 'Monaco EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('bfe3d562-2c11-46fc-b1cf-88844d8eff68', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'c2c97bc1-402e-47fc-9a18-826abacdd091', 7, 'Jakarta EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('728d7a6d-8296-4f71-9935-280e4061247f', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'cf6b6fdb-0285-4f51-85d5-d835c4fe4063', 8, 'Portland EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('6993b379-34fe-4f6d-8c31-207b4c5e9c39', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'c2571251-7af3-4d77-89c6-91bcf0fa2d40', 9, 'Rome EPrix');
INSERT INTO public.events (id, season, venue, index, label) VALUES ('163fe178-f3c7-4eac-9191-1d37c0eaa958', 'ced08a1d-a1ce-4943-a9b0-a958ee19ddeb', 'a77d06c6-a381-430b-a420-22166956d24b', 10, 'London EPrix');


--
-- Data for Name: driver_standings; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--



--
-- Data for Name: step_types; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

INSERT INTO public.step_types (id, label) VALUES ('3c56e36c-14f8-4c69-8976-c22105359e5a', 'Qualifying');
INSERT INTO public.step_types (id, label) VALUES ('b3418e4c-73dc-4304-b45a-c64bc9fb3f27', 'Race');
INSERT INTO public.step_types (id, label) VALUES ('2848eaca-c593-4b97-95de-6b71c114b0b5', 'Sprint Qualifying');
INSERT INTO public.step_types (id, label) VALUES ('c18bc57f-decf-4747-9c94-35da22b064dd', 'Testing Day 2');
INSERT INTO public.step_types (id, label) VALUES ('fe338f15-30ff-4d25-9afb-69d1cddd4ea1', 'Testing Day 1');
INSERT INTO public.step_types (id, label) VALUES ('b3c7ae16-eb2e-49b7-8734-1058db189a96', 'Testing Day 3');
INSERT INTO public.step_types (id, label) VALUES ('ac38384c-2789-4c16-89d1-ecdd338ef198', 'Qualifying Qualifying');
INSERT INTO public.step_types (id, label) VALUES ('4009943f-3390-4d7f-a3cd-474328820013', 'Day 1');
INSERT INTO public.step_types (id, label) VALUES ('2a143500-eba9-41de-9dd5-dd0285421af0', 'Day 2');
INSERT INTO public.step_types (id, label) VALUES ('c94d976d-704c-4592-a86b-29859c9d95b4', 'Day 3');
INSERT INTO public.step_types (id, label) VALUES ('8091c3c7-1e9d-4726-a87b-ba659e86f80a', 'Sprint Race');
INSERT INTO public.step_types (id, label) VALUES ('d00b18ba-da9f-450f-af47-90dbddb8be6c', 'Race 1');
INSERT INTO public.step_types (id, label) VALUES ('946a2e6c-7ffe-4a00-be96-f33815c091f0', 'Race 2');
INSERT INTO public.step_types (id, label) VALUES ('15ac3019-b681-45f6-a1cd-01239a387b17', 'Feature Race');
INSERT INTO public.step_types (id, label) VALUES ('c79a321e-a9ed-41fb-a7a9-21950823bf45', 'Test Day 1');
INSERT INTO public.step_types (id, label) VALUES ('d5ef9e9a-5c98-492b-979e-8ce58ec1b767', 'Test Day 2');


--
-- Data for Name: event_steps; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--

INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('847fafcd-27fd-46bd-8ad1-b8994bf259ef', '2972f677-aa6d-4f99-bdc0-4b1685f34bf6', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-03-17 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c65683bc-f8d2-40bf-9ae7-a94122f88d06', '93897fce-b1c0-4fed-8411-afeddd483937', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-04 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5d9e5f7b-e9d1-49ba-950d-a702e367f718', '51d9d255-07ec-4934-a314-d8f47302d0b7', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-03-19 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('335e83b4-82ec-4a83-b0ad-5e986fa70fb6', '51d9d255-07ec-4934-a314-d8f47302d0b7', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-03-20 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('15b4688c-1d11-41b3-bfa2-d1814a6eeb77', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-03-26 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('66ce89ce-6150-4db7-928e-80e7dccfdb87', 'd41a2e1d-ac4e-42e9-a72d-54abd0620887', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-03-27 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ff84931d-95b4-4d31-943f-497afa69ce23', '226da250-a350-457d-864d-ebe8f47d9f73', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-06-11 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2b461fad-ce83-4bf6-8f3f-f74241ecaf84', '31939543-3a9f-45f4-aa4e-78f8f2aade70', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-04-09 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c6e5d69a-9dd5-40c2-8245-abe5eb311476', '31939543-3a9f-45f4-aa4e-78f8f2aade70', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-04-10 05:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bddbb6b4-e15a-48b8-9aca-a0d028bb7201', 'b7941e54-d8a0-4785-85b4-36316bff4b71', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-04-22 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('39c033cc-8b28-4587-ad95-4ce334e5c3bc', 'b7941e54-d8a0-4785-85b4-36316bff4b71', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2022-04-23 14:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e9a88ef1-6672-46ca-bf46-1957e0046fb0', 'b7941e54-d8a0-4785-85b4-36316bff4b71', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-04-24 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('92eaaf65-61c8-4fb0-8c45-791ba43da5e1', '2f11aff6-baae-4bd6-9426-678cc33281e0', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-05-07 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('74b8e5dd-838c-49d4-8dff-77b7dc940b98', '2f11aff6-baae-4bd6-9426-678cc33281e0', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-05-08 19:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ed57ee09-030b-483b-92d9-d63a43b7b9f8', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-05-22 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('65200673-c8a9-4100-aea8-3d8ec3e07d0d', 'b3f67443-31cb-4263-84b3-e861ddba4e4b', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-05-21 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('381d713b-9c03-4a67-8886-ff5dc1947d27', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-05-28 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b3e9ef4c-9a22-4525-930d-a56cdecad111', '6afbb5e2-e169-411f-9f30-e1919f4fbb6a', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-05-29 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a8ebaa61-053b-4c39-b9bd-be494c5aa82a', '226da250-a350-457d-864d-ebe8f47d9f73', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-06-12 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('11f50c3b-2c72-4174-af53-8eef221e0ff4', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-06-18 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('99d4c260-715b-4af2-b82b-1a6beac17a6d', '967cb75d-60f6-4998-9e60-ba45e94c3ee8', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-06-19 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3a6228dd-9a7b-420d-928d-d8c4b6ee62a0', '5565ab3f-ee96-474f-a266-b1a83885aaa9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-02 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2fc09c78-4025-4e95-b8f0-84aaa413521c', '5565ab3f-ee96-474f-a266-b1a83885aaa9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-03 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('0d7a764d-e6d1-4d0f-87c7-cc6b8aaa9e63', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-08 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('6c65d484-4acb-4cbb-95f0-6ba00187c6af', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2022-07-09 14:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('50247b27-8dec-4684-be79-ce39fa564109', 'f5aabd63-10dc-4bf7-908f-b78b48cfe866', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-10 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2cfcdc47-ddaf-4067-8c33-90ed8d8a518e', '0889b10a-a652-4965-a3ea-757de0d2ccb1', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-16 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('02b4cc72-9de0-4134-a04d-40a5d4503337', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-23 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('89e06d9b-abd8-423e-aac7-f4e96fe07739', 'fcbe8a95-ab56-4ec6-815b-e12e3d3eda54', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-24 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c4437881-ff19-4acf-9d70-f3746dd7a944', '20f33cc2-edf0-462d-813f-3c88853c5935', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-07-30 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7786220c-ffdd-41a1-b86c-b8f289028cc0', '20f33cc2-edf0-462d-813f-3c88853c5935', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-07-31 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('df50d9cc-92ab-46a5-9406-692675c7ed45', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-08-27 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d198694d-26d2-4d83-8df6-aa3ed81c478f', '9edfaf1e-6fe2-44ba-92fb-0b946fc64f59', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-08-28 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1532135e-00d2-4d62-94c4-8f473ff1e9c2', '1c6c16fb-e472-48d3-8bc6-7000a342f881', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-09-03 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b0cae2ba-cb8b-4a89-8973-93b8cbafe728', '1c6c16fb-e472-48d3-8bc6-7000a342f881', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-09-04 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e06f7484-7287-4cd6-a6b5-0b8946f87e8f', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-09-10 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8ca32f26-fe7a-4bad-941f-b403ea958642', 'dd2bc70a-71d8-48c2-b36e-01a765d7a5e9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-09-11 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('906f9587-2270-4a2d-a832-39720aaea23a', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-01 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('84636143-56dd-45dc-80bd-eb4f08cd2694', '1ca163e0-f93a-41e0-b935-ebd1ea86bf97', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-02 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c22ef212-bc2a-47c5-a24c-7c656c346297', '44d55619-b5ca-4d9d-b33d-293cd87272af', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-08 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('94df2b80-7a55-42e1-aa42-f8cf8b6c7cb7', '44d55619-b5ca-4d9d-b33d-293cd87272af', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-09 05:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('81c0dcc5-0c66-4bbe-96c4-21e379a07935', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-22 22:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('143b8039-53d9-443e-9fe7-0821253bd841', 'c83f5cc3-47ed-46b6-baeb-3bfe254b5647', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-23 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('36037f01-d98c-476e-97b4-c78518c7122a', '384ca8e9-9563-4908-b487-024f0653a0cc', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-10-29 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fdc57e37-65e8-4475-a354-6fc83db52de7', '384ca8e9-9563-4908-b487-024f0653a0cc', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-10-30 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('904302d8-94b7-4a0b-905c-00fefc028e29', '9165f124-a621-476f-a419-d1fc4727ac27', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-11-11 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('cd34909c-cd6f-4c08-9075-9abfb3253931', '9165f124-a621-476f-a419-d1fc4727ac27', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2022-11-12 19:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('423b3b12-f4b5-47d5-ab10-178e478421c4', '9165f124-a621-476f-a419-d1fc4727ac27', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-11-13 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('acdc2598-3b6a-4ae9-aacd-6e185fe3ae8f', 'debaeb4c-92e5-4309-9004-e85727ff7b12', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2022-11-19 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('97db111b-44f7-4093-9ed3-267606ec704f', 'debaeb4c-92e5-4309-9004-e85727ff7b12', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2022-11-20 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ffa2f5a2-0b36-49c9-b502-532b1cc55bcb', '91fd7aca-83ab-456f-9142-53697c355c76', 'b3c7ae16-eb2e-49b7-8734-1058db189a96', '2023-02-25 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ff69e800-4f10-490f-9d78-82e08581e02a', '91fd7aca-83ab-456f-9142-53697c355c76', 'c18bc57f-decf-4747-9c94-35da22b064dd', '2023-02-24 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b47b6fde-bdcf-41c1-bb3d-26ccc31d08dc', '91fd7aca-83ab-456f-9142-53697c355c76', 'fe338f15-30ff-4d25-9afb-69d1cddd4ea1', '2023-02-23 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('09274fcb-b9f2-4b67-8d00-1d9e4ce72950', '1cf287d9-1532-4cd1-9651-d4dc7fcb446c', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-29 12:45:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('00caaec9-e682-4dd9-b7fc-29ff9ccedcd0', 'c921f7b9-d477-479f-8aa0-2a476624ee12', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-18 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d54f7e26-aa37-4636-9abd-495c78b52b73', 'ad730445-6797-444c-9232-4f8c531a49a1', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-03-05 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8d5b2330-150c-42ab-9242-d4f5e394d946', 'ad730445-6797-444c-9232-4f8c531a49a1', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-03-04 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b46e2e64-fe5a-4f6e-b07b-200678d84477', '4c5b906c-7485-4e48-aaf0-538aec307bf8', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-03-18 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('64ff06eb-75f0-4c24-8cc6-76e6d465a047', '4c5b906c-7485-4e48-aaf0-538aec307bf8', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-03-19 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bd4eb8c0-a69f-42c3-8f8d-d3910bc46a81', '2a8051a2-0575-4618-b90b-6bd45f6730db', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-04-01 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c6b50cc3-e9e0-491c-aec5-ed3aa332de5e', '2a8051a2-0575-4618-b90b-6bd45f6730db', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-02 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d923fe46-d893-4e58-ade4-d985dc394518', '954f773a-dde5-4636-b766-0854419872bb', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-05-06 21:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('38591453-17c5-47f3-86c7-b5e8b8560dac', '954f773a-dde5-4636-b766-0854419872bb', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-05-07 20:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('01d920d1-169d-47c0-bed2-eefc9c64189f', '7681d69c-031d-4dd5-b0e7-297d2d1bfda9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-05-20 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('71fad8be-63e5-4784-9ee3-4d954d22268d', '7681d69c-031d-4dd5-b0e7-297d2d1bfda9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-05-21 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3ce422dc-8572-48b3-83fc-bff0c595e893', '09318e72-4327-4e63-8e9d-1b5f3d99fa4d', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-05-27 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('f7aa0d4d-9cf8-44b2-a709-5ce380c8138b', '09318e72-4327-4e63-8e9d-1b5f3d99fa4d', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-05-28 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8fb3a85d-d4b5-45fe-8323-e36d2056d913', '485fb859-40e6-452f-bb39-e08ad61b91d9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-06-03 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fdcb53cb-24ef-4ad0-a421-f2d4a679003a', '485fb859-40e6-452f-bb39-e08ad61b91d9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-04 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('400bd1e5-3d6b-4a0d-99c6-8a09fe8f1de0', 'c921f7b9-d477-479f-8aa0-2a476624ee12', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-06-17 21:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1b7161be-0e81-44a0-9322-60da78052324', '13ac76b8-a636-4914-b76b-e84a939424cf', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-07-08 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('45ac7200-c06b-4ee1-9bcb-fc3ba72c1e81', '13ac76b8-a636-4914-b76b-e84a939424cf', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-09 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('adc72e3f-2765-49e2-a285-fa7500120fa5', 'c96020a3-16f1-40cf-9bb5-ae4c34ca2dfa', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-07-22 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('133e3587-b82d-43a2-a043-f8682c5f9df8', 'c96020a3-16f1-40cf-9bb5-ae4c34ca2dfa', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-23 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('f15583a0-41fc-4994-ab98-08a8c1f122d0', '01e3829e-5017-4af7-9d73-509485417eb0', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-10 16:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e2098890-fa9b-4b44-99a4-769ac9697275', '4fafd909-d7c4-4a29-b0be-b4d46a6266f4', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-08-26 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d44bbdd9-74a4-4744-afe3-15f4ad1aeeb8', '4fafd909-d7c4-4a29-b0be-b4d46a6266f4', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-08-27 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9dbdecd6-54a8-48cb-afb0-c6421195d91d', '0e3e5120-29e9-48cb-bd93-abb75c03acdc', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-02 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('69d7237f-3b52-4181-a764-bfd672e8f93f', '0e3e5120-29e9-48cb-bd93-abb75c03acdc', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-03 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('febf33e2-5922-4c57-a133-6b4a297dcabb', '4f00b004-429c-4900-8b00-3e47a9760279', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-16 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ffbcd38a-8835-4acc-8c36-b7070bbe52ab', '4f00b004-429c-4900-8b00-3e47a9760279', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-17 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('43ff45aa-0820-4d4e-8623-dd67d9fc8400', '68dc4465-976b-4b05-85b3-a726f0938b4a', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-23 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bd1ca022-23ae-4305-bc5f-f2c9ddae7b26', '68dc4465-976b-4b05-85b3-a726f0938b4a', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-24 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1e66ed18-e704-4121-b121-ec40a0fb1a0e', 'dac38989-9a52-4812-87d9-e7074a4484e4', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-10-06 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('6e89756f-e921-4f03-9939-120b71383925', 'dac38989-9a52-4812-87d9-e7074a4484e4', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-08 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e6ff581f-f9c7-4337-85bc-0695e6186113', '688a4832-32f7-47b6-b45b-2e7b9f6a5f19', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-10-20 22:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('756994fd-0a58-494f-a2d2-8114b1f538f0', '688a4832-32f7-47b6-b45b-2e7b9f6a5f19', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2023-10-21 23:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c45e88fe-78c5-4dca-b55e-33c7a0e70bb2', '688a4832-32f7-47b6-b45b-2e7b9f6a5f19', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-22 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b2c7cb94-f5ac-4dc3-b707-99333eeeab20', 'dac38989-9a52-4812-87d9-e7074a4484e4', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2023-10-07 15:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7c18e9cd-d1b9-4be7-b5c3-df5af195a5a0', '58882396-4113-4643-96ec-4538b194a38c', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-10-28 22:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d92637b5-d5ef-4227-a00b-cbfe198d97d8', '58882396-4113-4643-96ec-4538b194a38c', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-29 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c8caf940-8010-4de2-a5d8-9daf6b4d651c', 'e1463a2d-b2e5-4b37-b400-e7af50bcfad5', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-11-03 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b0363d5e-9db5-464d-946a-4ea65c961b78', 'e1463a2d-b2e5-4b37-b400-e7af50bcfad5', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2023-11-04 18:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8a0c3d3c-2baa-4453-9c1b-45fe65225580', 'e1463a2d-b2e5-4b37-b400-e7af50bcfad5', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-05 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2d3bba6b-3e74-423d-a3d8-a3d348e83108', '3bf31a09-736d-4320-8032-e4bf48eaa266', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-11-18 08:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('518cd802-4899-454d-8afe-6908c41c6826', '3bf31a09-736d-4320-8032-e4bf48eaa266', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-19 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e00a6176-ddd3-4e62-8a43-7b29ef9d5371', 'c1c9a8e7-ef72-4a41-b483-91242633534b', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-11-25 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('311fedc6-5f70-41e9-be35-a6553a2aec81', 'c1c9a8e7-ef72-4a41-b483-91242633534b', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-26 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d20f66a3-5c33-47e7-a7f5-061a72dacdbf', '78c22dd9-be1e-4945-960c-01658201044d', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-04-28 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('71aa4da5-359e-47e4-ad1c-2a166c04f16f', '78c22dd9-be1e-4945-960c-01658201044d', 'ac38384c-2789-4c16-89d1-ecdd338ef198', '2023-04-29 14:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('43a6477a-42bf-4e04-b070-d78cb8fcf4eb', '78c22dd9-be1e-4945-960c-01658201044d', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-30 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e74674c6-a95e-4740-b768-569b2e90ab37', '885e9dd1-5abe-428e-8019-f603ceae0685', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-06-30 16:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9bc1454d-62e5-464e-bbc4-cd3fc7123b87', '94a14683-4f80-4c85-bfa9-ae2e638262ac', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-19 12:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7170b091-4f7e-4252-9f80-888be4e67dcb', '885e9dd1-5abe-428e-8019-f603ceae0685', 'ac38384c-2789-4c16-89d1-ecdd338ef198', '2023-07-01 15:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c46fa55f-cd99-4687-81f8-711aeca49f0f', '885e9dd1-5abe-428e-8019-f603ceae0685', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-02 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('eef88a30-5f85-49ee-92eb-66d7acf3bf10', '35246f2b-ec9a-43dc-aa33-d92250fde07f', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-07-28 16:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('161dd483-fb11-49ed-9fa4-22f0efcd172f', '35246f2b-ec9a-43dc-aa33-d92250fde07f', '2848eaca-c593-4b97-95de-6b71c114b0b5', '2023-07-29 15:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2a66bca0-6a3e-4578-a00e-f35a80789b66', '35246f2b-ec9a-43dc-aa33-d92250fde07f', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-30 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('46f296b8-44e6-46d7-bc0d-f6a70a0bb32a', '0fd11778-2cea-4da8-b2a8-69c6e953db4b', '4009943f-3390-4d7f-a3cd-474328820013', '2023-02-10 02:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('94c7b90f-ce96-41b7-ad5e-c8bc8f5bde3c', '0fd11778-2cea-4da8-b2a8-69c6e953db4b', '2a143500-eba9-41de-9dd5-dd0285421af0', '2023-02-11 02:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2b23b149-dfbc-423f-81f3-45208a51fbbd', '0fd11778-2cea-4da8-b2a8-69c6e953db4b', 'c94d976d-704c-4592-a86b-29859c9d95b4', '2023-02-12 02:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('33cf5996-3736-4aee-b0c0-32b2ae4dd975', 'bf81cdb9-3918-4878-94de-39387b56c160', '4009943f-3390-4d7f-a3cd-474328820013', '2023-03-11 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('038f4a41-194d-4b6a-a1d2-fddcca3509c0', 'bf81cdb9-3918-4878-94de-39387b56c160', '2a143500-eba9-41de-9dd5-dd0285421af0', '2023-03-12 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2bf6a9de-76fa-4071-b5af-ea53718957d6', '06818f75-91cc-4076-8ffd-0a25b8576e69', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-03-25 10:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2b09884a-d870-4b21-871f-65dd95acdbe9', '06818f75-91cc-4076-8ffd-0a25b8576e69', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-03-25 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('925f84d7-2d68-4e78-a012-25cbbed85655', '06818f75-91cc-4076-8ffd-0a25b8576e69', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-03-26 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4e698e8c-7718-45ec-9c8d-c27a38c5f651', '9039a428-128a-4bbe-9973-2ab76ea06d27', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-04-01 14:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('42f38636-9524-4303-94da-fb3b388e9ef5', '9039a428-128a-4bbe-9973-2ab76ea06d27', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-04-01 19:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('57a5b804-6495-4137-86cb-6ae98c5832cf', '9039a428-128a-4bbe-9973-2ab76ea06d27', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-02 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7f78287b-7c29-4753-a4ca-f99728b241b6', '9da2f180-99b3-4d52-a39d-0bee47bd47f1', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-04-15 16:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3ee88721-8bec-45ac-b5d5-a810c8c4c376', '9da2f180-99b3-4d52-a39d-0bee47bd47f1', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-04-15 21:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('18dd40d0-0fbe-4fec-b9fa-129250256620', '9da2f180-99b3-4d52-a39d-0bee47bd47f1', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-16 20:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3ed54efd-df18-49ef-af35-2e708fbdca7a', 'e73b1f3a-420a-4ea8-9a99-e8f13095746b', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-04-29 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d902a16d-8412-454f-b73d-f1c3dac034dc', 'e73b1f3a-420a-4ea8-9a99-e8f13095746b', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-04-29 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3377ebd7-7983-4a1f-88e6-05e08d3a4b15', 'e73b1f3a-420a-4ea8-9a99-e8f13095746b', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-30 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('84d0bbf1-7e5d-449d-b7b1-7e0264204378', '4eadaf5b-710c-4a67-811f-9369fd8daf4f', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-05-13 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('067c52d8-4897-4592-8249-8d095f9cd311', '4eadaf5b-710c-4a67-811f-9369fd8daf4f', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-05-13 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b01d3638-2929-4683-b3a5-6f1747d29c81', '4eadaf5b-710c-4a67-811f-9369fd8daf4f', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-05-14 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b65cd0db-5430-4809-b22f-45e1c574a11f', '7bf959f6-077e-4f14-9d4c-0615e2e49daa', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-06-10 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7aded47c-4df7-4cbd-84ad-e3b1a64c6735', '7bf959f6-077e-4f14-9d4c-0615e2e49daa', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-06-10 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b366d97a-d877-47ba-823d-136a4a6f56f1', '7bf959f6-077e-4f14-9d4c-0615e2e49daa', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-11 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('754cd480-3cdd-4ae9-9d0b-91eb8d6ad232', 'f213fe1b-bf87-422c-a9f8-ed265ef888e3', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-06-17 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('267924c7-e615-4ba5-8279-6cba035111af', 'f213fe1b-bf87-422c-a9f8-ed265ef888e3', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-06-17 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('0ab4411f-ca7f-43a3-93ad-3d5322aa2b90', 'f213fe1b-bf87-422c-a9f8-ed265ef888e3', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-18 18:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('59cff3ef-d032-4cdd-a567-d06ab004b847', '09d53848-e46d-4725-a43a-fa8565266339', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-06-24 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7b2f6836-4b3c-4347-be5c-00e5d759d7b8', '09d53848-e46d-4725-a43a-fa8565266339', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-06-24 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('807540f3-da52-4145-97a3-6b314279205f', '09d53848-e46d-4725-a43a-fa8565266339', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-25 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ba201d72-1064-473d-8838-c9c599ee2b21', '17f98797-e35b-45dc-81f0-2adb24548930', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-07-08 05:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ecee9f03-0395-4a45-8d98-49ef58620491', '17f98797-e35b-45dc-81f0-2adb24548930', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-07-08 10:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d6a889f8-2dcd-4a91-9f67-0c7213afa185', '17f98797-e35b-45dc-81f0-2adb24548930', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-09 09:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('26c2e0ac-035c-4078-895d-fea571d06a57', 'deb34f29-09ea-4444-84e1-02659cbd898d', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-07-05 10:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('54f862ad-fe2b-4127-b4fb-a37d364a3cb1', 'deb34f29-09ea-4444-84e1-02659cbd898d', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-07-05 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2c066929-fe64-4732-98a0-090b71edbf34', 'deb34f29-09ea-4444-84e1-02659cbd898d', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-06 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d2822612-9e34-4d29-bc65-5a2d9e8d77bd', '7dc23ce0-288d-4b14-ab3c-8c14924445ae', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-08-19 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a72afe73-e5a7-40ea-be01-16e5ffb82827', '7dc23ce0-288d-4b14-ab3c-8c14924445ae', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-08-19 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3ce11ec4-4842-4043-8f65-9c65947b6f91', '7dc23ce0-288d-4b14-ab3c-8c14924445ae', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-08-20 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('616598f2-e26e-4859-9b33-9a220c89a78e', '20a81e5d-4c41-4168-82f8-f59634648b8b', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-02 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('532974c3-c93f-4652-acf5-a9426df35f60', '20a81e5d-4c41-4168-82f8-f59634648b8b', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-09-02 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c85a8cc5-1200-44b6-bff4-3bb028996b96', '20a81e5d-4c41-4168-82f8-f59634648b8b', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-03 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bce34e46-11d1-4977-bb4e-9fc572db3ff7', '1eb2eba8-5ecc-4ebc-b0b8-dbb21d2725d9', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-09 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7fbca67d-349e-44a7-9361-2a189aa6eb65', '1eb2eba8-5ecc-4ebc-b0b8-dbb21d2725d9', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-09-09 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7dd568c4-25f1-4058-9acf-eeebb1fc52ea', '1eb2eba8-5ecc-4ebc-b0b8-dbb21d2725d9', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-10 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('72110968-feed-496f-95d4-fbdf5a5fdcd4', '9ce570e7-da1b-449c-8c19-39da3a41211a', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-23 06:20:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4d5f3a03-3362-497e-88b1-97f2126ac0f9', '9ce570e7-da1b-449c-8c19-39da3a41211a', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-09-23 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fc1d3c8c-b8c8-4200-9c1a-557563e6a05a', '9ce570e7-da1b-449c-8c19-39da3a41211a', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-24 09:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('54a7ce97-895c-4f36-8e72-fc0437d6059d', '69522e63-ef6a-4a60-a0f3-3d59a46f26b5', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-09-30 02:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('708eb764-b4af-4a6c-baad-9379f87853ec', '69522e63-ef6a-4a60-a0f3-3d59a46f26b5', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-09-30 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e1ec1fb8-883d-4b9b-b67e-697a6cd52a60', '69522e63-ef6a-4a60-a0f3-3d59a46f26b5', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-01 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('62d9983a-caa1-44bd-8719-d278249a8646', 'f46886fd-b2da-4e87-9a72-b2b3cac22f73', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-10-14 03:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d0efe764-25cc-4a75-8dff-bdd664a3a8f4', 'f46886fd-b2da-4e87-9a72-b2b3cac22f73', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-10-14 08:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('caae1026-a851-44f4-8910-0eb53c9b5953', 'f46886fd-b2da-4e87-9a72-b2b3cac22f73', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-15 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('23278f07-dd0a-4b68-bb89-6456fcb0fe63', '4dbe7d13-5c25-4dd2-9246-fd56d2fa4e5d', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-10-21 00:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('21a09404-24f1-4ddb-a913-6486f2a30a8d', '4dbe7d13-5c25-4dd2-9246-fd56d2fa4e5d', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-10-21 05:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9592c249-5d44-4c3b-b8c2-6c4be6cad7bd', '4dbe7d13-5c25-4dd2-9246-fd56d2fa4e5d', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-22 04:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('977b5fb5-806d-48bb-b7fa-d04173aa19ab', '3d85572c-eb4a-460f-96ec-b9cb2f996226', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-10-28 04:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('2f24a138-a8d8-4395-9207-869ff1ca6a89', '3d85572c-eb4a-460f-96ec-b9cb2f996226', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-10-28 09:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('79346289-34a7-4ecf-aeee-ca89830dc1b4', '3d85572c-eb4a-460f-96ec-b9cb2f996226', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-29 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b4140293-a965-45aa-8fac-8375cb808f40', '2b9b022e-3571-4bb3-bad4-b5f3417e44d8', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-11-11 02:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('93688d3a-5931-4d5d-9a2c-e2922a95774a', '2b9b022e-3571-4bb3-bad4-b5f3417e44d8', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-11-11 07:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e79a90e6-31e5-41d5-bd06-7ea278452a79', '2b9b022e-3571-4bb3-bad4-b5f3417e44d8', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-12 06:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7a0e3179-e15b-4de6-92ee-2d06c0c31ee9', '900927bf-e651-48b9-b202-943396e5fe5a', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-11-18 12:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('cdc9017f-b16a-480d-93a4-8b73d9b67e79', '900927bf-e651-48b9-b202-943396e5fe5a', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-11-18 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8be97dd6-d92e-4756-823d-c9177e13b485', '900927bf-e651-48b9-b202-943396e5fe5a', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-19 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e0c31915-ab8e-4189-9ed1-d0d002deddc6', '3b17d019-e1a4-40c2-a7cf-9de47261f5d5', '3c56e36c-14f8-4c69-8976-c22105359e5a', '2023-11-25 09:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a3c8e180-18f3-486c-93b0-8eefe9256b65', '3b17d019-e1a4-40c2-a7cf-9de47261f5d5', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-11-25 14:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ec014abd-2334-438d-a5b8-0c5f66d4e374', '3b17d019-e1a4-40c2-a7cf-9de47261f5d5', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-11-26 13:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('65490acc-6462-4b17-a56c-c20cbf8b164a', '2df96fc9-2d08-4a7b-a973-4e6a78e67e14', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-10 11:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('260ac02d-7b8f-4f81-b470-156fdcd98698', '9e4f0f2e-750a-49c6-8b1f-0ad46a728371', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-01-28 13:40:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5ddd65cd-316c-4c6e-b997-efe620395e47', 'ac547b54-2f7e-41d0-a944-50ffdfe658f2', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-03-18 10:10:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fe4ded35-a7da-42bb-8847-b766b86764a1', 'dcbf25ee-1b60-48fc-a103-45ed9aa3f0b6', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-15 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7dfd9724-ded5-42e2-863e-16efd2adaf04', '7dae93a2-26c7-49f7-9eab-09b5942bcca0', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-05-14 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ee858784-b0bd-48a5-a1f6-fd1e3c0bd534', 'd6c1da4b-a41f-4955-a065-381a80645b1c', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-25 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('24cfce48-75ae-4bad-9aed-e515ca223057', 'efd97afb-2461-4a64-b273-b1a695859781', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-09 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4481b4c9-d28c-4295-bc11-d0281835b74f', 'e43f86a8-e125-4e9b-88f0-58eb6aa0d9e8', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-22 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('f0033364-8510-4e17-8d9d-1336c2609edf', '068e7ec8-8f12-44dc-82eb-f88737ee461e', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-08-06 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4390ef72-7a80-4b67-b6b4-2e8a8b0fb3ed', '217131e2-173c-4dd0-87af-eb75bf7ba0bf', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-08-27 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('48c4bca5-9db4-4292-9a3d-617a551a78f7', '2261119c-6781-412c-9e94-34a869b2ea64', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-17 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a053abf1-6c69-4f48-9e8e-ed60047949aa', '7064a92b-ec41-4b89-ac9e-de4b066869c7', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-14 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('162954a5-e240-496f-a165-61d70327f3a6', '25ab3fdb-d2fd-4a99-a83a-2351c276a0cc', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-04-23 15:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4be25835-d4fc-4508-aed8-cdb9b1271c46', '25bdb04c-6648-423f-9399-8e8b2dea0d3b', 'd00b18ba-da9f-450f-af47-90dbddb8be6c', '2023-05-14 11:05:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1a853543-b01a-4d80-9280-d722c2b55fd9', '25bdb04c-6648-423f-9399-8e8b2dea0d3b', '946a2e6c-7ffe-4a00-be96-f33815c091f0', '2023-05-14 16:10:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('279adb4f-8980-49aa-b8bb-3a9457506bac', '48f6cf8f-f088-41fc-8f1d-324adfefc522', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-04 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('f9362cf3-099c-45ab-bab6-2c09377cfc11', '013bf375-bcd3-4b19-b2d0-85541b322b02', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-01 01:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7025dce5-5d84-46c4-a809-0fafc8825139', '4c663866-cb66-4072-89b6-c855668c315f', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-16 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('03b91011-c067-4653-b4eb-bb7d7fd21b5b', 'b958b6f5-f905-4fae-97ba-f6ad1e345bf5', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-07-30 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fe8025f7-2d87-4086-a8a7-a43a1c073e88', 'fe01c86d-561d-4bd1-ad58-a33b8e97a747', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-03 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9ca9972c-3339-46d7-81e4-9fc0c25e3236', '95ed073c-20a7-4bdb-9e46-a1a6a5e9450d', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-09-17 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4c60c2e4-2a2a-4fff-8792-d9a0808ccf6a', '5e4d9d8d-95ba-46f1-96b3-3b57acc581fe', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-01 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a4736d5a-9364-44af-9545-5160a701e433', '77076e82-22f9-4cc0-be96-dc9bb2bd6c2c', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-10-15 12:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('da211fd6-12a5-498d-be3f-e6a1febc2414', '2a133fdf-4b7d-4b9a-9b03-27457896051d', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-03-04 16:40:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('adba9e32-a8ec-4276-9ac4-9730020221d9', '2a133fdf-4b7d-4b9a-9b03-27457896051d', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-03-05 10:40:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('3ed7fbb8-9949-4622-80ba-d3231fb90781', 'a7251c75-c48a-4ce2-8c86-8a4a5625c06c', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-03-18 12:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1e961a88-31f1-4e1c-9e73-bb5891d47236', 'a7251c75-c48a-4ce2-8c86-8a4a5625c06c', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-03-19 13:35:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('d99a7c05-8fd9-459e-b1b8-2762796c1995', 'fd6da8d7-c86b-4b01-8ea0-90c6265a82c2', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-04-01 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e54267ca-45e2-4819-b4f8-8a933a78d401', 'fd6da8d7-c86b-4b01-8ea0-90c6265a82c2', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-04-02 08:05:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5e3eb216-1a3b-4197-94fa-d97c9509fd75', '8758408b-8979-4306-9ffc-b5e87184f91d', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-04-29 10:30:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('cbb61f86-d5d3-41bf-885e-947a18a427db', '8758408b-8979-4306-9ffc-b5e87184f91d', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-04-30 08:05:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('ca7eb083-5b1c-4ffb-95ed-966b59c317d4', 'de100482-1595-43cd-9d9b-5fce2229219c', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-05-20 16:55:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('7ee8e719-e652-49b4-aa82-43770e88336a', 'de100482-1595-43cd-9d9b-5fce2229219c', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-05-21 09:20:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a35b6bd0-578f-4ec6-9fa1-1c811c3c290a', 'e19bb729-9f1e-4d34-bb15-f5675bfd43b1', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-05-27 16:40:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('24e228bf-2626-462d-a9fe-f05e95a3fc9e', 'e19bb729-9f1e-4d34-bb15-f5675bfd43b1', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-05-28 08:50:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('da4de8cd-c92e-4ae1-8bd4-29bf6eda6e75', '736547a1-4baf-4121-953c-c6b082490117', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-06-03 16:40:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bfaa3eff-48e8-4524-a46e-763fd1471813', '736547a1-4baf-4121-953c-c6b082490117', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-06-04 10:35:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fcd92aa3-3bb5-49ff-b7f7-f1ad99b5fabd', '6f3333eb-eeb4-47f0-a967-438b322c9f79', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-07-01 16:55:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c82c78f4-6a78-462c-abc1-c290a3d632cf', '6f3333eb-eeb4-47f0-a967-438b322c9f79', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-07-02 09:05:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('48b8d303-74e5-4ddf-9800-7c4785978399', '789a2650-5dae-4d41-8b23-b4f8756347c6', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-07-08 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b2ad0e4a-8760-41ac-83aa-93d2be9e7703', '789a2650-5dae-4d41-8b23-b4f8756347c6', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-07-09 10:05:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5b384e01-bb0e-4677-90bb-4760152bacf2', 'ee3ae2af-601b-426a-b8e5-8f652280462f', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-07-22 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('4e219caf-c39e-478d-aaae-0918e6212449', 'ee3ae2af-601b-426a-b8e5-8f652280462f', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-07-23 10:35:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('a69560be-a44f-4e3a-89d8-ebe83b9334a7', '551a5e3d-7830-4576-b058-076eb1790765', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-07-29 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('10136cd2-f817-4239-95f5-3cecae5eb55f', '551a5e3d-7830-4576-b058-076eb1790765', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-07-30 09:20:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('8f751242-a059-4590-afeb-4b8d53657f57', 'c1f6bc2c-8c35-4b3b-a4fc-876697eaed4b', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-08-26 16:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5968c618-c859-4cec-856c-a5f1db906e99', 'c1f6bc2c-8c35-4b3b-a4fc-876697eaed4b', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-08-27 09:20:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('212319e5-7ade-4c27-95b8-0981d64cd6a6', '920c1719-187d-4f79-9244-52c86046b76f', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-09-02 17:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('1f7f6576-bbd0-4020-bccf-35e646fa815c', '920c1719-187d-4f79-9244-52c86046b76f', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-09-03 09:05:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('c49fafa2-d8d2-4527-8fb0-6f53fe761d11', '151e5a6d-be04-4794-ae56-e27b214adac3', '8091c3c7-1e9d-4726-a87b-ba659e86f80a', '2023-11-25 12:20:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('028b2cbd-38cc-4339-81ef-f204ee3f9188', '151e5a6d-be04-4794-ae56-e27b214adac3', '15ac3019-b681-45f6-a1cd-01239a387b17', '2023-11-26 09:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('bf9b0a1a-40e8-40fe-96bd-bfe0fdd5e766', '6119ab77-2b69-45d7-978c-e5684a6e4eaf', 'c79a321e-a9ed-41fb-a7a9-21950823bf45', '2023-03-07 08:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('eb1c67fe-7691-44e1-b538-223596e69c88', '6119ab77-2b69-45d7-978c-e5684a6e4eaf', 'd5ef9e9a-5c98-492b-979e-8ce58ec1b767', '2023-03-08 08:00:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('669b5e8d-4b0f-49b1-b093-77be7d1a574c', 'e645eab3-b510-43a1-9f18-0b5188b9e131', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-01-14 20:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b72a5634-299a-4e42-864d-d2124bb387a6', 'd49a877f-1937-43f2-85d3-f035751f350e', 'd00b18ba-da9f-450f-af47-90dbddb8be6c', '2023-01-27 17:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fdbcd400-efbc-4992-97e6-7943a81206ce', 'd49a877f-1937-43f2-85d3-f035751f350e', '946a2e6c-7ffe-4a00-be96-f33815c091f0', '2023-01-28 17:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('60b045da-e70b-421d-9226-b134c0cbad24', '59ec6f9f-6ad9-4e76-883a-e14a1bf76fb5', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-02-11 09:33:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5ec25c77-a99a-4dae-8b99-9dc2d3890399', 'bc005609-a1c2-4825-9239-52601a5346ca', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-02-25 14:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('b2f28858-36a1-4cfe-aed5-faa9158a9889', 'be4c80e7-24d2-4ee1-ac05-7e62c83e8542', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-03-25 17:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('06e4f3b9-2d59-43eb-98ce-7212bc438c33', 'b4eadc1f-3546-4464-a254-0c295b1c0781', 'd00b18ba-da9f-450f-af47-90dbddb8be6c', '2023-04-22 14:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('5442e1a2-6ec6-46c3-9671-b3e011842f85', 'b4eadc1f-3546-4464-a254-0c295b1c0781', '946a2e6c-7ffe-4a00-be96-f33815c091f0', '2023-04-23 14:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('be53a1a7-1c53-43c3-9563-0d2cfca488ff', '46e44679-cbad-4c0a-8ec1-d858483f6672', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-05-06 14:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('dd7535c3-bd6e-4622-b639-f99cdca492df', 'bfe3d562-2c11-46fc-b1cf-88844d8eff68', 'd00b18ba-da9f-450f-af47-90dbddb8be6c', '2023-06-03 09:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('e96a0dc6-91b0-4d24-a6eb-742a83c5933a', 'bfe3d562-2c11-46fc-b1cf-88844d8eff68', '946a2e6c-7ffe-4a00-be96-f33815c091f0', '2023-06-04 09:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('61a13de5-14af-40da-afcc-d9d462e1b8bd', '728d7a6d-8296-4f71-9935-280e4061247f', 'b3418e4c-73dc-4304-b45a-c64bc9fb3f27', '2023-06-24 08:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('285fae28-f94a-4fb0-aa9a-1c661e10cf37', '6993b379-34fe-4f6d-8c31-207b4c5e9c39', 'd00b18ba-da9f-450f-af47-90dbddb8be6c', '2023-07-15 14:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('fa70b8dd-f94d-45d0-8720-754303efefb1', '6993b379-34fe-4f6d-8c31-207b4c5e9c39', '946a2e6c-7ffe-4a00-be96-f33815c091f0', '2023-07-16 14:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('9cbed9d8-ef5c-4cb1-b0c7-ba5b136e585a', '163fe178-f3c7-4eac-9191-1d37c0eaa958', 'd00b18ba-da9f-450f-af47-90dbddb8be6c', '2023-07-29 17:03:00');
INSERT INTO public.event_steps (id, event, type, date_time) VALUES ('604544cc-e1fb-4ad8-850b-a443280c248c', '163fe178-f3c7-4eac-9191-1d37c0eaa958', '946a2e6c-7ffe-4a00-be96-f33815c091f0', '2023-07-30 17:03:00');


--
-- Data for Name: racers; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
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
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('3334f7ab-920b-4b1e-a945-0ba12d5b8164', 'eb970384-3630-4620-87b3-bdf0f2f4384c', '368de2ea-cc01-412e-a670-65f3512daf10', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('23ef3b77-204e-4ef5-8502-461f5b0b1359', '8c481614-90d4-4178-928f-591294d2caef', '93cd5944-2646-4681-ba0c-fe24be31a2d7', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('c5210928-c3f3-404a-99b5-b599fbbf2015', 'a490d12e-2720-429f-8c88-fbb63faccc2c', 'f3296964-0bf7-4ad6-aa47-3fc349d66720', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('cc3da460-1263-4895-bb75-8293c1159ee7', 'c064d0a4-4392-4b9e-9f3d-1684f542edb9', '4da2cb59-5bc4-4502-983c-8ab5ba156f78', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('be3c1609-52d9-4c00-adbe-2a6ea25a2ae8', '47ff7165-fb78-4672-88f3-a476463ecd7e', 'd98aaa41-6948-4216-9585-c84faedcd17c', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('41881da2-5429-4a0e-84cb-d3c14aafc922', 'ec7726a2-8144-40c4-a9a8-510a4e48fcb8', 'b8242bfc-831e-41d3-99ca-a15906bef28b', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('74d6cacd-f9d7-4a64-a034-0cada33b7821', '701d5d67-322a-4698-807e-790dc36ef2c9', '0c7f0cf0-8d2f-438f-b1d8-c15be2e8cc96', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('bd5f4538-586c-4d16-a3aa-e597b3a69e3d', '6f18d71b-2d3c-452a-be0e-2851bc4df46e', 'b480ff53-a91e-49bb-8de2-ba673501982f', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('3aa04a6b-72f8-4138-aea4-4fdcb1a14be7', '4d80e144-7260-4c17-a36b-842a59ddd3c7', '886072d0-3f71-44d9-a28e-5d1120b34239', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('85f4765c-e890-43fc-8499-eaed3e44a657', 'c58d852c-0475-48fa-8064-b078b5ef92ba', '5db217dc-d298-479e-ac24-0e2e0178d30a', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('688d5199-0cd5-436b-8573-44e56d9a40a5', 'cc7a3100-8dae-40e1-bae2-119fa42c2901', 'caf2d7ff-8372-4043-b79d-e2062d799da6', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('1b97bb37-f111-4fcb-9d58-fdeb58e91160', '33ad85b8-1c68-4cbf-8a88-0c10ed6cbf83', '88038686-7a0e-4d23-8043-93e56e48a804', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('970f04f4-99ab-4580-af72-2184e90992d5', 'db0a5c33-5af5-42af-8b9a-c6470e9d085d', 'f4c60528-4f64-47d0-b460-15c0ec4d69fe', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('e82c4fd2-44e8-4f8d-9522-117558a205aa', 'e0b6d951-9f75-4d7d-9f7a-c5dc48a796c8', '1dfe05c7-7693-4a94-9333-54ebd485916a', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('f4aad547-d9ae-4e90-bb05-66b7166f2218', 'db82c1a8-df57-4573-ba00-c053b0dba526', '72dbaa68-4dc3-4087-90b0-8153cb441738', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('6757a39f-f0c7-4a6e-be22-5c43575615df', 'af083867-6b05-4dc2-8ed0-25464f0232ec', '494f6b1d-7622-4032-bf04-182a0dee404e', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('de8e6942-cc57-4e17-a0ac-b6cb09f94563', '0f4b7fc5-c714-41d1-b860-7aaf226057cf', '376f88ca-b07a-4802-b117-799421598d03', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('2902530d-e57e-4576-a527-ff776e136149', 'd45c62d1-de1e-441e-bfc5-9349ba814465', '59dc6702-fde8-4b2f-b04e-1a0f8ffebe38', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('3f81efe8-d22f-4515-a2bf-26beff401d4e', '56073ab5-f373-478f-a041-d734e0af0f0f', 'c0b2c74e-762e-4b0e-8875-cbaf7fab4717', '2023-01-01 00:00:00', '2023-12-31 23:59:59');
INSERT INTO public.racers (id, car, driver, startdate, enddate) VALUES ('c714d861-3434-48c0-af35-020f63353861', '86bd6f56-be33-411b-bafc-d2254eb67a27', '043f15b7-7e92-4a7e-8e2d-08db6ae3c283', '2023-01-01 00:00:00', '2023-12-31 23:59:59');


--
-- Data for Name: results; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--



--
-- Data for Name: team_presentations; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--



--
-- Data for Name: team_standings; Type: TABLE DATA; Schema: public; Owner: motorsporttracker
--



--
-- PostgreSQL database dump complete
--
