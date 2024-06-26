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

$prefix kishlin:motorsport-stats:season:scrap "IMSA SportsCar Championship"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-stats:calendar:scrap "IMSA SportsCar Championship" "$year"
done

$prefix kishlin:motorsport:championship-presentation:add "IMSA SportsCar Championship" "imsa-sportscar-championship.svg" "fefffe"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-cache:calendar:sync "IMSA SportsCar Championship" "$year"
  $prefix kishlin:motorsport-cache:season-events:sync "IMSA SportsCar Championship" "$year"
done
