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
        <Grid item sx={{ mb: 2 }} columns={{ xs: 1 }}>
            <Grid container flexDirection="column">
                {eventsJSX}
            </Grid>
        </Grid>
    );
};

export default ScheduleDay;
