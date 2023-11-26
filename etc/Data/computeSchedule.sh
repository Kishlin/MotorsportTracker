#! /bin/bash

php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "World Endurance Championship"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "W Series"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "MotoGP"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "IMSA SportsCar Championship"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "GT World Challenge Europe Sprint Cup"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "GT World Challenge Europe"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "Formula E"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "F4 France"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "GT4 France"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "FIA Formula 3 Championship"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "FIA Formula 2 Championship"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "ADAC GT Masters"
php /app/MotorsportTracker/apps/Backoffice/bin/console kishlin:motorsport-cache:schedule:compute -v "Formula One"

