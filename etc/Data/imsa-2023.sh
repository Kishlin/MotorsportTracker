#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:venue:add "Daytona International Speedway" "us"
$prefix kishlin:motorsport:venue:add "Long Beach Street Circuit" "us"
$prefix kishlin:motorsport:venue:add "WeatherTech Raceway Laguna Seca" "us"
$prefix kishlin:motorsport:venue:add "Watkins Glen International" "us"
$prefix kishlin:motorsport:venue:add "Canadian Tire Motorsport Park" "ca"
$prefix kishlin:motorsport:venue:add "Lime Rock Park" "us"
$prefix kishlin:motorsport:venue:add "Road America" "us"
$prefix kishlin:motorsport:venue:add "VIRginia International Raceway" "us"
$prefix kishlin:motorsport:venue:add "Indianapolis Motor Speedway" "us"
$prefix kishlin:motorsport:venue:add "Michelin Raceway Road Atlanta" "us"

$prefix kishlin:motorsport:event:add imsa 2023 "Daytona International Speedway" 0 "Rolex 24 at Daytona"
$prefix kishlin:motorsport:event:add imsa 2023 "Sebring" 1 "12 Hours of Sebring"
$prefix kishlin:motorsport:event:add imsa 2023 "Long Beach Street Circuit" 2 "Acura GP of Long Beach"
$prefix kishlin:motorsport:event:add imsa 2023 "WeatherTech Raceway Laguna Seca" 3 "Motul Course de Monterey"
$prefix kishlin:motorsport:event:add imsa 2023 "Watkins Glen International" 4 "6 Hours of The Glen"
$prefix kishlin:motorsport:event:add imsa 2023 "Canadian Tire Motorsport Park" 5 "Chevrolet GP"
$prefix kishlin:motorsport:event:add imsa 2023 "Lime Rock Park" 6 "FCP Euro Northeast GP"
$prefix kishlin:motorsport:event:add imsa 2023 "Road America" 7 "IMSA Sportscar Weekend"
$prefix kishlin:motorsport:event:add imsa 2023 "VIRginia International Raceway" 8 "Michelin GT Challenge at VIR"
$prefix kishlin:motorsport:event:add imsa 2023 "Indianapolis Motor Speedway" 9 "IMSA Battle on The Bricks"
$prefix kishlin:motorsport:event:add imsa 2023 "Michelin Raceway Road Atlanta" 10 "Motul Petit Le Mans"


$prefix kishlin:motorsport:event-step:add imsa 2023 "Daytona International Speedway" "Race" "2023-01-28 13:40:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Sebring" "Race" "2023-03-18 10:10:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Long Beach Street Circuit" "Race" "2023-04-15 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "WeatherTech Raceway Laguna Seca" "Race" "2023-05-14 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Watkins Glen International" "Race" "2023-06-25 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Canadian Tire Motorsport Park" "Race" "2023-07-09 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Lime Rock Park" "Race" "2023-07-22 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Road America" "Race" "2023-08-06 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "VIRginia International Raceway" "Race" "2023-08-27 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Indianapolis Motor Speedway" "Race" "2023-09-17 01:00:00"
$prefix kishlin:motorsport:event-step:add imsa 2023 "Michelin Raceway Road Atlanta" "Race" "2023-10-14 01:00:00"
