#! /bin/bash

if [ $# -lt 1 ]
then
    echo "Usage: $(basename "$0") PREFIX"
    echo "Example: $(basename "$0") \"docker-compose exec backoffice bin/console\""
    exit 1
fi

start=2014
if [[ "$2" != "" ]]; then
    start="$2"
fi

end=2023
if [[ "$3" != "" ]]; then
    end="$3"
fi

prefix=$1

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-stats:championship:sync imsa-sportscar-championship "$year"
done

$prefix kishlin:motorsport:championship-presentation:add imsa-sportscar-championship "imsa.svg" "#fefffe"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-cache:calendar:sync imsa-sportscar-championship "$year"
done
