#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:driver:add Piastri Oscar au
$prefix kishlin:motorsport:driver:add Sargeant Logan us

$prefix kishlin:motorsport:car:add formula1 2023 "Alfa Romeo" 24
$prefix kishlin:motorsport:car:add formula1 2023 "Alfa Romeo" 77
$prefix kishlin:motorsport:car:add formula1 2023 "Alpha Tauri" 21
$prefix kishlin:motorsport:car:add formula1 2023 "Alpha Tauri" 22
$prefix kishlin:motorsport:car:add formula1 2023 "Alpine" 10
$prefix kishlin:motorsport:car:add formula1 2023 "Alpine" 31
$prefix kishlin:motorsport:car:add formula1 2023 "Aston Martin" 14
$prefix kishlin:motorsport:car:add formula1 2023 "Aston Martin" 18
$prefix kishlin:motorsport:car:add formula1 2023 "Ferrari" 16
$prefix kishlin:motorsport:car:add formula1 2023 "Ferrari" 55
$prefix kishlin:motorsport:car:add formula1 2023 "Haas" 20
$prefix kishlin:motorsport:car:add formula1 2023 "Haas" 27
$prefix kishlin:motorsport:car:add formula1 2023 "Mclaren" 4
$prefix kishlin:motorsport:car:add formula1 2023 "Mclaren" 81
$prefix kishlin:motorsport:car:add formula1 2023 "Mercedes" 44
$prefix kishlin:motorsport:car:add formula1 2023 "Mercedes" 63
$prefix kishlin:motorsport:car:add formula1 2023 "Red Bull" 1
$prefix kishlin:motorsport:car:add formula1 2023 "Red Bull" 11
$prefix kishlin:motorsport:car:add formula1 2023 "Williams" 2
$prefix kishlin:motorsport:car:add formula1 2023 "Williams" 23

$prefix kishlin:motorsport:driver-move:add zhou formula1 2023 "Alfa Romeo" 24 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add bottas formula1 2023 "Alfa Romeo" 77 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add nyck formula1 2023 "Alpha Tauri" 21 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add tsunoda formula1 2023 "Alpha Tauri" 22 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add gasly formula1 2023 "Alpine" 10 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add ocon formula1 2023 "Alpine" 31 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add alonso formula1 2023 "Aston Martin" 14 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add stroll formula1 2023 "Aston Martin" 18 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add leclerc formula1 2023 "Ferrari" 16 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add sainz formula1 2023 "Ferrari" 55 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add magnussen formula1 2023 "Haas" 20 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Hulkenberg formula1 2023 "Haas" 27 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Norris formula1 2023 "Mclaren" 4 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Piastri formula1 2023 "Mclaren" 81 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Hamilton formula1 2023 "Mercedes" 44 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Russell formula1 2023 "Mercedes" 63 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Verstappen formula1 2023 "Red Bull" 1 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Perez formula1 2023 "Red Bull" 11 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Sargeant formula1 2023 "Williams" 2 "2023-01-01 00:00:00"
$prefix kishlin:motorsport:driver-move:add Albon formula1 2023 "Williams" 23 "2023-01-01 00:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "STC" "Practice 1" "2023-03-17 13:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "STC" "Practice 2" "2023-03-17 17:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "STC" "Practice 3" "2023-03-18 13:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "STC" "Qualifying" "2023-03-18 17:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "STC" "Race" "2023-03-19 17:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Australian" "Practice 1" "2023-03-31 02:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Australian" "Practice 2" "2023-03-31 06:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Australian" "Practice 3" "2023-04-01 02:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Australian" "Qualifying" "2023-04-01 06:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Australian" "Race" "2023-04-02 06:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Azerbaijan" "Practice 1" "2023-04-28 10:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Azerbaijan" "Qualifying" "2023-04-28 14:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Azerbaijan" "Practice 2" "2023-04-29 10:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Azerbaijan" "Qualifying Qualifying" "2023-04-29 14:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Azerbaijan" "Race" "2023-04-30 12:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Crypto" "Practice 1" "2023-05-05 19:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Crypto" "Practice 2" "2023-05-05 23:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Crypto" "Practice 3" "2023-05-06 17:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Crypto" "Qualifying" "2023-05-06 21:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Crypto" "Race" "2023-05-07 20:30:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Emilia" "Practice 1" "2023-05-19 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Emilia" "Practice 2" "2023-05-19 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Emilia" "Practice 3" "2023-05-20 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Emilia" "Qualifying" "2023-05-20 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Emilia" "Race" "2023-05-21 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Grand Prix de Monaco" "Practice 1" "2023-05-26 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grand Prix de Monaco" "Practice 2" "2023-05-26 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grand Prix de Monaco" "Practice 3" "2023-05-27 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grand Prix de Monaco" "Qualifying" "2023-05-27 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grand Prix de Monaco" "Race" "2023-05-28 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "AWS" "Practice 1" "2023-06-02 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "AWS" "Practice 2" "2023-06-02 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "AWS" "Practice 3" "2023-06-03 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "AWS" "Qualifying" "2023-06-03 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "AWS" "Race" "2023-06-04 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Grand Prix du Canada" "Practice 1" "2023-06-16 18:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Grand Prix du Canada" "Practice 2" "2023-06-16 22:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Grand Prix du Canada" "Practice 3" "2023-06-17 17:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Grand Prix du Canada" "Qualifying" "2023-06-17 21:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Grand Prix du Canada" "Race" "2023-06-18 19:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Grosser" "Practice 1" "2023-06-30 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grosser" "Qualifying" "2023-06-30 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grosser" "Practice 2" "2023-07-01 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grosser" "Qualifying Qualifying" "2023-07-01 15:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Grosser" "Race" "2023-07-02 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Aramco British" "Practice 1" "2023-07-07 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Aramco British" "Practice 2" "2023-07-07 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Aramco British" "Practice 3" "2023-07-08 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Aramco British" "Qualifying" "2023-07-08 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Aramco British" "Race" "2023-07-09 15:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Hungarian" "Practice 1" "2023-07-21 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Hungarian" "Practice 2" "2023-07-21 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Hungarian" "Practice 3" "2023-07-22 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Hungarian" "Qualifying" "2023-07-22 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Hungarian" "Race" "2023-07-23 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Belgian" "Practice 1" "2023-07-28 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Belgian" "Qualifying" "2023-07-28 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Belgian" "Practice 2" "2023-07-29 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Belgian" "Sprint Qualifying" "2023-07-29 15:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Belgian" "Race" "2023-07-30 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Dutch" "Practice 1" "2023-08-25 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Dutch" "Practice 2" "2023-08-25 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Dutch" "Practice 3" "2023-08-26 10:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Dutch" "Qualifying" "2023-08-26 14:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Dutch" "Race" "2023-08-27 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Gran Premio D'Italia" "Practice 1" "2023-09-01 12:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Gran Premio D'Italia" "Practice 2" "2023-09-01 16:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Gran Premio D'Italia" "Practice 3" "2023-09-02 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Gran Premio D'Italia" "Qualifying" "2023-09-02 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Pirelli Gran Premio D'Italia" "Race" "2023-09-03 14:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Singapore" "Practice 1" "2023-09-15 10:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Singapore" "Practice 2" "2023-09-15 14:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Singapore" "Practice 3" "2023-09-16 10:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Singapore" "Qualifying" "2023-09-16 14:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Singapore" "Race" "2023-09-17 13:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo Japanese" "Practice 1" "2023-09-22 03:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo Japanese" "Practice 2" "2023-09-22 07:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo Japanese" "Practice 3" "2023-09-23 03:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo Japanese" "Qualifying" "2023-09-23 07:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo Japanese" "Race" "2023-09-24 06:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Qatar" "Practice 1" "2023-10-06 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Qatar" "Qualifying" "2023-10-06 15:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Qatar" "Practice 2" "2023-10-07 11:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Qatar" "Sprint" "2023-10-07 15:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Qatar" "Race" "2023-10-08 15:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo United States" "Practice 1" "2023-10-20 18:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo United States" "Qualifying" "2023-10-20 22:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo United States" "Practice 2" "2023-10-21 19:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo United States" "Sprint Qualifying" "2023-10-21 23:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Lenovo United States" "Race" "2023-10-22 20:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Gran Premio de la Ciudad" "Practice 1" "2023-10-27 19:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Gran Premio de la Ciudad" "Practice 2" "2023-10-27 23:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Gran Premio de la Ciudad" "Practice 3" "2023-10-28 18:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Gran Premio de la Ciudad" "Qualifying" "2023-10-28 22:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Gran Premio de la Ciudad" "Race" "2023-10-29 20:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Grande Prêmio" "Practice 1" "2023-11-03 14:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Grande Prêmio" "Qualifying" "2023-11-03 18:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Grande Prêmio" "Practice 2" "2023-11-04 14:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Grande Prêmio" "Sprint Qualifying" "2023-11-04 18:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Rolex Grande Prêmio" "Race" "2023-11-05 17:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Silver Las Vegas" "Practice 1" "2023-11-17 04:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Silver Las Vegas" "Practice 2" "2023-11-17 08:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Silver Las Vegas" "Practice 3" "2023-11-18 04:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Silver Las Vegas" "Qualifying" "2023-11-18 08:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Heineken Silver Las Vegas" "Race" "2023-11-19 06:00:00"

$prefix kishlin:motorsport:event-step:add formula1 2023 "Etihad Airways Abu Dhabi" "Practice 1" "2023-11-24 09:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Etihad Airways Abu Dhabi" "Practice 2" "2023-11-24 13:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Etihad Airways Abu Dhabi" "Practice 3" "2023-11-25 10:30:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Etihad Airways Abu Dhabi" "Qualifying" "2023-11-25 14:00:00"
$prefix kishlin:motorsport:event-step:add formula1 2023 "Etihad Airways Abu Dhabi" "Race" "2023-11-26 13:00:00"
