import React from 'react';
import { Grid } from '@mui/material';

import { DailyEventsSchedule, MotorsportEvent } from '../../Shared/Types';
import ScheduleEvent from './ScheduleEvent';

declare type ScheduleDayProps = {
    events: DailyEventsSchedule,
};

const ScheduleDay: React.FunctionComponent<ScheduleDayProps> = ({ events }) => {
    const eventsJSX = Object.values(events).map(
        (event: MotorsportEvent) => <ScheduleEvent key={event.reference} event={event} />,
    );

    return (
        <Grid item container flexDirection="column" sx={{ my: 1 }}>
            {eventsJSX}
        </Grid>
    );
};

export default ScheduleDay;
