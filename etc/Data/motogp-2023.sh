#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:venue:add "Sepang International Circuit" "my"
$prefix kishlin:motorsport:venue:add "Autódromo Internacional do Algarve" "pt"
$prefix kishlin:motorsport:venue:add "Termas de Río Hondo" "ar"
$prefix kishlin:motorsport:venue:add "Circuito de Jerez - Angel Nieto" "es"
$prefix kishlin:motorsport:venue:add "Le Mans" "fr"
$prefix kishlin:motorsport:venue:add "Autodromo Internazionale del Mugello" "it"
$prefix kishlin:motorsport:venue:add "Sachesenring" "de"
$prefix kishlin:motorsport:venue:add "TT Circuit Assen" "nl"
$prefix kishlin:motorsport:venue:add "Sokol International Racetrack" "kz"
$prefix kishlin:motorsport:venue:add "Misano World Circuit Marco Simoncelli" "it"
$prefix kishlin:motorsport:venue:add "Buddh Internacional Circuit" "in"
$prefix kishlin:motorsport:venue:add "Mobility Resort Motegi" "jp"
$prefix kishlin:motorsport:venue:add "Mandalika International Street Circuit" "id"
$prefix kishlin:motorsport:venue:add "Phillip Island" "au"
$prefix kishlin:motorsport:venue:add "Chang International Circuit" "th"
$prefix kishlin:motorsport:venue:add "Circuit Ricardo Tormo" "es"

$prefix kishlin:motorsport:event:add motogp 2023 "sepang" 0 "Sepang MotoGP Official Test"
$prefix kishlin:motorsport:event:add motogp 2023 "algarve" 1 "Portimao MotoGP Official Test"
$prefix kishlin:motorsport:event:add motogp 2023 "Algarve" 2 "GP de Portugal"
$prefix kishlin:motorsport:event:add motogp 2023 "Termas de" 3 "GP Michelin de la República Argentina"
$prefix kishlin:motorsport:event:add motogp 2023 "Circuit of the Americas" 4 "Red Bull GP of The Americas"
$prefix kishlin:motorsport:event:add motogp 2023 "Angel Nieto" 5 "GP de España"
$prefix kishlin:motorsport:event:add motogp 2023 "Le Mans" 6 "Shark GP de France"
$prefix kishlin:motorsport:event:add motogp 2023 "Mugello" 7 "GP d'Italia Oakley"
$prefix kishlin:motorsport:event:add motogp 2023 "Sachesenring" 8 "Liqui moly MGP Deutschland"
$prefix kishlin:motorsport:event:add motogp 2023 "TT Circuit Assen" 9 "Motul TT Assen"
$prefix kishlin:motorsport:event:add motogp 2023 "Sokol" 10 "GP of Kazakhstan"
$prefix kishlin:motorsport:event:add motogp 2023 "Silverstone" 11 "Monster Energy British GP"
$prefix kishlin:motorsport:event:add motogp 2023 "Red Bull Ring" 12 "CrytoDATA MGP von Österreich"
$prefix kishlin:motorsport:event:add motogp 2023 "Barcelona" 13 "GP Monster Energy de Catalunya"
$prefix kishlin:motorsport:event:add motogp 2023 "Misano" 14 "GP di San Marino e della Riviera di Rimini"
$prefix kishlin:motorsport:event:add motogp 2023 "Buddh" 15 "GP of India"
$prefix kishlin:motorsport:event:add motogp 2023 "Motegi" 16 "MGP of Japan"
$prefix kishlin:motorsport:event:add motogp 2023 "Mandalika" 17 "Pertamina GP of Indonesia"
$prefix kishlin:motorsport:event:add motogp 2023 "Phillip Island" 18 "Animoca Brands Australian MGP"
$prefix kishlin:motorsport:event:add motogp 2023 "Chang" 19 "OR Thailand GP"
$prefix kishlin:motorsport:event:add motogp 2023 "Sepang" 20 "Petronas GP of Malaysia"
$prefix kishlin:motorsport:event:add motogp 2023 "Lusail" 21 "GP of Qatar"
$prefix kishlin:motorsport:event:add motogp 2023 "Tormo" 22 "GPM de la Comunitat Valenciana"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Sepang MotoGP Official Test" "Day 1" "2023-02-10 02:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Sepang MotoGP Official Test" "Day 2" "2023-02-11 02:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Sepang MotoGP Official Test" "Day 3" "2023-02-12 02:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Portimao MotoGP Official Test" "Day 1" "2023-03-11 10:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Portimao MotoGP Official Test" "Day 2" "2023-03-12 10:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP de Portugal" "Qualifying" "2023-03-25 10:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP de Portugal" "Sprint Race" "2023-03-25 15:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP de Portugal" "Race" "2023-03-26 14:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP Michelin de la República Argentina" "Qualifying" "2023-04-01 14:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP Michelin de la República Argentina" "Sprint Race" "2023-04-01 19:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP Michelin de la República Argentina" "Race" "2023-04-02 18:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Red Bull GP of The Americas" "Qualifying" "2023-04-15 16:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Red Bull GP of The Americas" "Sprint Race" "2023-04-15 21:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Red Bull GP of The Americas" "Race" "2023-04-16 20:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP de España" "Qualifying" "2023-04-29 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP de España" "Sprint Race" "2023-04-29 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP de España" "Race" "2023-04-30 14:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Shark GP de France" "Qualifying" "2023-05-13 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Shark GP de France" "Sprint Race" "2023-05-13 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Shark GP de France" "Race" "2023-05-14 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP d'Italia Oakley" "Qualifying" "2023-06-10 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP d'Italia Oakley" "Sprint Race" "2023-06-10 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP d'Italia Oakley" "Race" "2023-06-11 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Liqui moly MGP Deutschland" "Qualifying" "2023-06-17 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Liqui moly MGP Deutschland" "Sprint Race" "2023-06-17 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Liqui moly MGP Deutschland" "Race" "2023-06-18 18:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Motul TT Assen" "Qualifying" "2023-06-24 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Motul TT Assen" "Sprint Race" "2023-06-24 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Motul TT Assen" "Race" "2023-06-25 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of Kazakhstan" "Qualifying" "2023-07-08 05:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of Kazakhstan" "Sprint Race" "2023-07-08 10:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of Kazakhstan" "Race" "2023-07-09 09:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Monster Energy British GP" "Qualifying" "2023-07-05 10:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Monster Energy British GP" "Sprint Race" "2023-07-05 15:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Monster Energy British GP" "Race" "2023-07-06 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "CrytoDATA MGP von Österreich" "Qualifying" "2023-08-19 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "CrytoDATA MGP von Österreich" "Sprint Race" "2023-08-19 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "CrytoDATA MGP von Österreich" "Race" "2023-08-20 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP Monster Energy de Catalunya" "Qualifying" "2023-09-02 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP Monster Energy de Catalunya" "Sprint Race" "2023-09-02 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP Monster Energy de Catalunya" "Race" "2023-09-03 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP di San Marino e della Riviera di Rimini" "Qualifying" "2023-09-09 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP di San Marino e della Riviera di Rimini" "Sprint Race" "2023-09-09 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP di San Marino e della Riviera di Rimini" "Race" "2023-09-10 13:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of India" "Qualifying" "2023-09-23 06:20:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of India" "Sprint Race" "2023-09-23 10:30:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of India" "Race" "2023-09-24 09:30:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "MGP of Japan" "Qualifying" "2023-09-30 02:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "MGP of Japan" "Sprint Race" "2023-09-30 07:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "MGP of Japan" "Race" "2023-10-01 06:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Pertamina GP of Indonesia" "Qualifying" "2023-10-14 03:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Pertamina GP of Indonesia" "Sprint Race" "2023-10-14 08:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Pertamina GP of Indonesia" "Race" "2023-10-15 07:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Animoca Brands Australian MGP" "Qualifying" "2023-10-21 00:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Animoca Brands Australian MGP" "Sprint Race" "2023-10-21 05:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Animoca Brands Australian MGP" "Race" "2023-10-22 04:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "OR Thailand GP" "Qualifying" "2023-10-28 04:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "OR Thailand GP" "Sprint Race" "2023-10-28 09:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "OR Thailand GP" "Race" "2023-10-29 07:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "Petronas GP of Malaysia" "Qualifying" "2023-11-11 02:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Petronas GP of Malaysia" "Sprint Race" "2023-11-11 07:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "Petronas GP of Malaysia" "Race" "2023-11-12 06:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of Qatar" "Qualifying" "2023-11-18 12:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of Qatar" "Sprint Race" "2023-11-18 17:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GP of Qatar" "Race" "2023-11-19 17:00:00"

$prefix kishlin:motorsport:event-step:add motogp 2023 "GPM de la Comunitat Valenciana" "Qualifying" "2023-11-25 09:50:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GPM de la Comunitat Valenciana" "Sprint Race" "2023-11-25 14:00:00"
$prefix kishlin:motorsport:event-step:add motogp 2023 "GPM de la Comunitat Valenciana" "Race" "2023-11-26 13:00:00"
