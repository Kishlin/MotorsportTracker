#! /bin/bash

#if [ $# -lt 2 ]
#then
#    echo "Usage: $(basename "$0" "$1") COMMAND CHAMPIONSHIP"
#    echo "Example: $(basename "$0" "$1") \"docker-compose exec backoffice bin/console kishlin:motorsport-etl:season:scrap\" \"Formula One\""
#    exit 2
#fi

#start=2020
#if [[ "$3" != "" ]]; then
#    start="$3"
#fi
#
#end=2023
#if [[ "$4" != "" ]]; then
#    end="$4"
#fi
#
#target=$1
#championship=$2
#
#for year in $(seq "$start" "$end")
#do
#  $target "$championship" "$year"
#done

files=( 
	"24 Hours of Le Mans"
	"24 Hours of Daytona"
	"ADAC 24h Rennen Nürburgring"
	"FIA Formula One World Championship"
	"FIA Formula 2 Championship"
	"FIA Formula 3 Championship"
	"ABB FIA Formula E World Championship"
	"F4 French Championship"
	"F1 Academy"
	"FIA World Endurance Championship"
	"GT World Challenge Europe Endurance"
	"GT World Challenge Europe Sprint Cup"
	"FFSA GT4 France"
	"IMSA SportsCar Championship"
	"ADAC GT Masters"
	"FIM MotoGP World Championship"
	"W Series"
)

for value in "${files[@]}"
do
	php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:calendar:compute "$value"
done
