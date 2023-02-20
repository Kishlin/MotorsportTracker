#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:venue:add "Sebring International Raceway" "us"
$prefix kishlin:motorsport:venue:add "Fuji Speedway" "jp"

$prefix kishlin:motorsport:event:add wec 2023 "sebring" 0 "R1 Sebring | 1000 Miles"
$prefix kishlin:motorsport:event:add wec 2023 "algarve" 1 "R2 Portimao | 6H"
$prefix kishlin:motorsport:event:add wec 2023 "francorchamps" 2 "R3 Spa-Francorchamps | 6H"
$prefix kishlin:motorsport:event:add wec 2023 "lemans" 3 "R4 Le Mans | 24H"
$prefix kishlin:motorsport:event:add wec 2023 "monza" 4 "R5 Monza | 6H"
$prefix kishlin:motorsport:event:add wec 2023 "fuji" 5 "R6 Fuji | 6H"
$prefix kishlin:motorsport:event:add wec 2023 "bahrain" 6 "R7 Bahrain | 8H"

$prefix kishlin:motorsport:event-step:add wec 2023 "R1" "Race" "2023-03-17 12:00:00"
$prefix kishlin:motorsport:event-step:add wec 2023 "R2" "Race" "2023-04-16 12:00:00"
$prefix kishlin:motorsport:event-step:add wec 2023 "R3" "Race" "2023-04-29 12:45:00"
$prefix kishlin:motorsport:event-step:add wec 2023 "R4" "Race" "2023-06-10 16:00:00"
$prefix kishlin:motorsport:event-step:add wec 2023 "R5" "Race" "2023-07-19 12:30:00"
$prefix kishlin:motorsport:event-step:add wec 2023 "R6" "Race" "2023-09-10 11:00:00"
$prefix kishlin:motorsport:event-step:add wec 2023 "R7" "Race" "2023-11-04 14:00:00"
