#! /bin/bash

if [ $# -lt 1 ]
then
    echo "Usage: $(basename "$0") PREFIX"
    echo "Example: $(basename "$0") \"docker-compose exec backoffice bin/console\""
    exit 1
fi

start=1950
if [[ "$2" != "" ]]; then
    start="$2"
fi

end=2023
if [[ "$3" != "" ]]; then
    end="$3"
fi

prefix=$1

$prefix kishlin:motorsport-stats:season:scrap "Formula One"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-stats:calendar:scrap "Formula One" "$year"
done

$prefix kishlin:motorsport:championship-presentation:add "Formula One" f1.svg "#e00000"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-cache:calendar:sync "Formula One" "$year"
done
