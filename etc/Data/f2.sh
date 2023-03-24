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

$prefix kishlin:motorsport-stats:season:scrap "FIA Formula 2 Championship"

#for year in $(seq "$start" "$end")
#do
#  $prefix kishlin:motorsport-stats:calendar:scrap "FIA Formula 2 Championship" "$year"
#done
#
#$prefix kishlin:motorsport:championship-presentation:add "FIA Formula 2 Championship" "formula-2.svg" "#043961"

for year in $(seq "$start" "$end")
do
  $prefix kishlin:motorsport-cache:calendar:sync "FIA Formula 2 Championship" "$year"
done
