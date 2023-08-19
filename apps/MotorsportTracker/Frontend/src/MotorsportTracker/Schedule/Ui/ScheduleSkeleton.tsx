'use client';

import { FunctionComponent } from 'react';
import Grid from '@mui/material/Grid';

import ScheduleEventSkeleton from './ScheduleEventSkeleton';

const ScheduleSkeleton: FunctionComponent = () => (
    <Grid item container flexDirection="column" columns={{ xs: 1 }}>
        <Grid item container flexDirection="column" sx={{ my: 1 }}>
            <ScheduleEventSkeleton />
            <ScheduleEventSkeleton />
        </Grid>
        <Grid item container flexDirection="column" sx={{ my: 1 }}>
            <ScheduleEventSkeleton />
            <ScheduleEventSkeleton />
            <ScheduleEventSkeleton />
        </Grid>
    </Grid>
);

export default ScheduleSkeleton;

