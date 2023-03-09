#! /bin/bash

if [ $# -lt 1 ]
then
    echo "Usage: $(basename "$0") PREFIX"
    echo "Example: $(basename "$0") \"docker-compose exec backoffice bin/console\""
    exit 1
fi

start=2017
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
  $prefix kishlin:motorsport-stats:championship:sync fia-formula-2-championship "$year"
done

$prefix kishlin:motorsport:championship-presentation:add fia-formula-2-championship "f2.svg" "#043961"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-cache:calendar:sync fia-formula-2-championship "$year"
done
