#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1


$prefix kishlin:motorsport:event:add formula2 2023 "bahrain" 0 "R1 Sakhir"
$prefix kishlin:motorsport:event:add formula2 2023 "jeddah" 1 "R2 Jeddah"
$prefix kishlin:motorsport:event:add formula2 2023 "melbourne" 2 "R3 Melbourne"
$prefix kishlin:motorsport:event:add formula2 2023 "baku" 3 "R4 Baku"
$prefix kishlin:motorsport:event:add formula2 2023 "enzoedino" 4 "R5 Imola"
$prefix kishlin:motorsport:event:add formula2 2023 "monaco" 5 "R6 Monte Carlo"
$prefix kishlin:motorsport:event:add formula2 2023 "barcelona" 6 "R7 Barcelona"
$prefix kishlin:motorsport:event:add formula2 2023 "redbullring" 7 "R8 Spielberg"
$prefix kishlin:motorsport:event:add formula2 2023 "silverstone" 8 "R9 Silverstone"
$prefix kishlin:motorsport:event:add formula2 2023 "hungaroring" 9 "R10 Budapest"
$prefix kishlin:motorsport:event:add formula2 2023 "francorchamps" 10 "R11 Spa-Francorchamps"
$prefix kishlin:motorsport:event:add formula2 2023 "zandvoort" 11 "R12 Zandvoort"
$prefix kishlin:motorsport:event:add formula2 2023 "monza" 12 "R13 Monza"
$prefix kishlin:motorsport:event:add formula2 2023 "yasmarina" 13 "R14 Yas Island"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Sakhir" "Sprint Race" "2023-03-04 16:40:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Sakhir" "Feature Race" "2023-03-05 10:40:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Jeddah" "Sprint Race" "2023-03-18 12:30:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Jeddah" "Feature Race" "2023-03-19 13:35:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Melbourne" "Sprint Race" "2023-04-01 10:30:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Melbourne" "Feature Race" "2023-04-02 08:05:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Baku" "Sprint Race" "2023-04-29 10:30:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Baku" "Feature Race" "2023-04-30 08:05:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Imola" "Sprint Race" "2023-05-20 16:55:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Imola" "Feature Race" "2023-05-21 09:20:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Carlo" "Sprint Race" "2023-05-27 16:40:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Carlo" "Feature Race" "2023-05-28 08:50:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Barcelona" "Sprint Race" "2023-06-03 16:40:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Barcelona" "Feature Race" "2023-06-04 10:35:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Spielberg" "Sprint Race" "2023-07-01 16:55:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Spielberg" "Feature Race" "2023-07-02 09:05:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Silverstone" "Sprint Race" "2023-07-08 17:00:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Silverstone" "Feature Race" "2023-07-09 10:05:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Budapest" "Sprint Race" "2023-07-22 17:00:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Budapest" "Feature Race" "2023-07-23 10:35:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Francorchamps" "Sprint Race" "2023-07-29 17:00:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Francorchamps" "Feature Race" "2023-07-30 09:20:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Zandvoort" "Sprint Race" "2023-08-26 16:00:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Zandvoort" "Feature Race" "2023-08-27 09:20:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Monza" "Sprint Race" "2023-09-02 17:00:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Monza" "Feature Race" "2023-09-03 09:05:00"

$prefix kishlin:motorsport:event-step:add formula2 2023 "Island" "Sprint Race" "2023-11-25 12:20:00"
$prefix kishlin:motorsport:event-step:add formula2 2023 "Island" "Feature Race" "2023-11-26 09:00:00"
