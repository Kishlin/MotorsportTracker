#! /bin/bash

if [ $# -lt 1 ]
then
    echo "Usage: $(basename "$0") PREFIX"
    echo "Example: $(basename "$0") \"docker-compose exec backoffice bin/console\""
    exit 1
fi

start=2019
if [[ "$2" != "" ]]; then
    start="$2"
fi

end=2023
if [[ "$3" != "" ]]; then
    end="$3"
fi

prefix=$1

$prefix kishlin:motorsport-stats:season:scrap "GT World Challenge Europe Sprint Cup"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-stats:calendar:scrap "GT World Challenge Europe Sprint Cup" "$year"
done

$prefix kishlin:motorsport:championship-presentation:add "GT World Challenge Europe Sprint Cup" "gt-world-challenge-europe-sprint-cup.svg" "ffcc00"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-cache:calendar:sync "GT World Challenge Europe Sprint Cup" "$year"
  $prefix kishlin:motorsport-cache:season-events:sync "GT World Challenge Europe Sprint Cup" "$year"
done
