#! /bin/bash

if [ $# -lt 2 ]
then
    echo "Usage: $(basename "$0" "$1") COMMAND CHAMPIONSHIP"
    echo "Example: $(basename "$0" "$1") \"docker-compose exec backoffice bin/console kishlin:motorsport-etl:season:scrap\" \"Formula One\""
    exit 2
fi

start=2020
if [[ "$3" != "" ]]; then
    start="$3"
fi

end=2023
if [[ "$4" != "" ]]; then
    end="$4"
fi

target=$1
championship=$2

for year in $(seq "$start" "$end")
do
  $target "$championship" "$year"
done

