#! /bin/bash

# Warning: Incomplete!

if [ $# -ne 1 ]
then
    echo "Usage: $(basename $0) PREFIX"
    echo "Example: $(basename $0) \"docker-compose exec backoffice bin/console\""
    exit 1
fi

prefix=$1

$prefix kishlin:motorsport:venue:add "Brands Hatch Circuit Kent" "gb"
$prefix kishlin:motorsport:venue:add "Hockenheimring" "de"
$prefix kishlin:motorsport:venue:add "Nürburgring" "de"

$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "paulricard" 0 "Paul Ricard Test Days"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "monza" 1 "R1 Monza Endurance"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "brandshatch" 2 "R2 Brands Hatch Sprint"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "paulricard" 3 "R3 Endurance Paul Ricard"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "francorchamps" 4 "R4 CrowdStrike 24 Hours of Spa"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "misano" 5 "R5 Sprint Misano"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "nürburgring" 6 "R6 Endurance Nürburgring"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "hockenheim" 7 "R7 Sprint Hockenheim"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "valencia" 8 "R8 Sprint Ricardo Tormo Valencia"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "barcelona" 9 "R9 Endurance Barcelona"
$prefix kishlin:motorsport:event:add gtworldchallengeeurope 2023 "zandvoort" 10 "R10 Sprint Zandvoort"

$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "Test Days" "Test Day 1" "2023-03-07 08:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "Test Days" "Test Day 2" "2023-03-08 08:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "monza" "Race" "2023-04-23 15:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "brandshatch" "Race 1" "2023-05-14 11:05:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "brandshatch" "Race 2" "2023-05-14 16:10:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "endurancepaulricard" "Race" "2023-06-04 01:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "crowdstrike" "Race" "2023-07-01 01:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "misano" "Race" "2023-07-16 12:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "nürburgring" "Race" "2023-07-30 12:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "hockenheim" "Race" "2023-09-03 12:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "valencia" "Race" "2023-09-17 12:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "barcelona" "Race" "2023-10-01 12:00:00"
$prefix kishlin:motorsport:event-step:add gtworldchallengeeurope 2023 "zandvoort" "Race" "2023-10-15 12:00:00"
