import React from 'react';
import { Grid, Typography } from '@mui/material';

import ScheduleEventsList from './ScheduleEventsList';
import ScheduleNavigation from './ScheduleNavigation';
import { EventsSchedule } from '../../Shared/Types';

declare type CalendarProps = {
    events: EventsSchedule,
    firstDay: Date,
    lastDay: Date,
    date: Date,
}

const Schedule: React.FunctionComponent<CalendarProps> = ({
    events,
    firstDay,
    lastDay,
    date,
}) => (
    <Grid container spacing={0} direction="column" sx={{ mx: 8, my: 2, maxWidth: '90%' }}>
        <Typography variant="h2" align="left" sx={{ my: 4 }}>Schedule</Typography>
        <ScheduleNavigation date={date} />
        <ScheduleEventsList firstDay={firstDay} lastDay={lastDay} events={events} />
    </Grid>
);

export default Schedule;
