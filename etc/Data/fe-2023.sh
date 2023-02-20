#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:venue:add "Mexico City" "mx"
$prefix kishlin:motorsport:venue:add "City of Diriyah" "az"
$prefix kishlin:motorsport:venue:add "City of Hyderabad" "in"
$prefix kishlin:motorsport:venue:add "City of Cape Town" "za"
$prefix kishlin:motorsport:venue:add "City of Sao Paulo" "br"
$prefix kishlin:motorsport:venue:add "City of Berlin" "de"
$prefix kishlin:motorsport:venue:add "City of Seoul" "kr"
$prefix kishlin:motorsport:venue:add "City of Jakarta" "id"
$prefix kishlin:motorsport:venue:add "City of Portland" "us"
$prefix kishlin:motorsport:venue:add "City of Rome" "it"
$prefix kishlin:motorsport:venue:add "City of London" "gb"

$prefix kishlin:motorsport:event:add formulae 2023 "MexicoCity" 0 "Mexico City EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfDiriyah" 1 "Diriyah EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfHyderabad" 2 "Hyderabad EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfCapeTown" 3 "Cape Town EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfSaoPaulo" 4 "Sao Paulo EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfBerlin" 5 "Berlin EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CircuitDeMonaco" 6 "Monaco EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfJakarta" 7 "Jakarta EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfPortland" 8 "Portland EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfRome" 9 "Rome EPrix"
$prefix kishlin:motorsport:event:add formulae 2023 "CityOfLondon" 10 "London EPrix"

$prefix kishlin:motorsport:event-step:add formulae 2023 "Mexico City EPrix" "Race" "2023-01-14 20:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Diriyah EPrix" "Race 1" "2023-01-27 17:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Diriyah EPrix" "Race 2" "2023-01-28 17:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Hyderabad EPrix" "Race" "2023-02-11 09:33:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Cape Town EPrix" "Race" "2023-02-25 14:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Sao Paulo EPrix" "Race" "2023-03-25 17:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Berlin EPrix" "Race 1" "2023-04-22 14:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Berlin EPrix" "Race 2" "2023-04-23 14:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Monaco EPrix" "Race" "2023-05-06 14:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Jakarta EPrix" "Race 1" "2023-06-03 09:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Jakarta EPrix" "Race 2" "2023-06-04 09:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Portland EPrix" "Race" "2023-06-24 08:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Rome EPrix" "Race 1" "2023-07-15 14:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "Rome EPrix" "Race 2" "2023-07-16 14:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "London EPrix" "Race 1" "2023-07-29 17:03:00"
$prefix kishlin:motorsport:event-step:add formulae 2023 "London EPrix" "Race 2" "2023-07-30 17:03:00"
