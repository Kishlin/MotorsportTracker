#! /bin/bash

if [ $# -lt 1 ]
then
    echo "Usage: $(basename "$0") PREFIX"
    echo "Example: $(basename "$0") \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:championship-presentation:add "ADAC GT Masters" "adac-gt-masters.svg" "#ffcf00"
$prefix kishlin:motorsport:championship-presentation:add "FIA Formula One World Championship" "fia-formula-one-world-championship.svg" "#e00000"
$prefix kishlin:motorsport:championship-presentation:add "FIA Formula 2 Championship" "fia-formula-2-championship.svg" "#043961"
$prefix kishlin:motorsport:championship-presentation:add "FIA Formula 3 Championship" "fia-formula-3-championship.svg" "#d02402"
$prefix kishlin:motorsport:championship-presentation:add "F4 French Championship" "f4-french-championship.svg" "#4a4a4a"
$prefix kishlin:motorsport:championship-presentation:add "ABB FIA Formula E World Championship" "abb-fia-formula-e-world-championship.svg" "#019dcb"
$prefix kishlin:motorsport:championship-presentation:add "FFSA GT4 France" "ffsa-gt4-france.svg" "#9a9a9a"
$prefix kishlin:motorsport:championship-presentation:add "GT World Challenge Europe Endurance" "gt-world-challenge-europe-endurance.svg" "#ffcc00"
$prefix kishlin:motorsport:championship-presentation:add "GT World Challenge Europe Sprint Cup" "gt-world-challenge-europe-sprint-cup.svg" "ffcc00"
$prefix kishlin:motorsport:championship-presentation:add "IMSA SportsCar Championship" "imsa-sportscar-championship.svg" "fefffe"
$prefix kishlin:motorsport:championship-presentation:add "FIM MotoGP World Championship" "fim-motogp-world-championship.svg" "#e07000"
$prefix kishlin:motorsport:championship-presentation:add "W Series" "w-series.svg" "#440099"
$prefix kishlin:motorsport:championship-presentation:add "FIA World Endurance Championship" "fia-world-endurance-championship.svg" "#0649a1"
$prefix kishlin:motorsport:championship-presentation:add "24 Hours of Daytona" "24-hours-of-daytona.png" "#64cb62"
$prefix kishlin:motorsport:championship-presentation:add "24 Hours of Le Mans" "24-hours-of-le-mans.png" "#063269"
