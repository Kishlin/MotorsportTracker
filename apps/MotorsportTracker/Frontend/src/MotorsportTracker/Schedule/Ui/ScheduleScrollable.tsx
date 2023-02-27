import React from 'react';
import { Grid, Typography } from '@mui/material';

import ScheduleEventsList from './ScheduleEventsList';
import { EventsSchedule } from '../../Shared/Types';

declare type CalendarProps = {
    events: EventsSchedule,
    firstDay: Date,
    lastDay: Date,
}

const ScheduleScrollable: React.FunctionComponent<CalendarProps> = ({ events, firstDay, lastDay }) => (
    <Grid container spacing={0} direction="column" sx={{ mx: 8, my: 2, maxWidth: '90%' }}>
        <Typography variant="h2" align="left" sx={{ my: 4 }}>Upcoming</Typography>
        <ScheduleEventsList firstDay={firstDay} lastDay={lastDay} events={events} />
    </Grid>
);

export default ScheduleScrollable;
