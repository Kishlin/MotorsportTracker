'use client';

import Skeleton from '@mui/material/Skeleton';
import { FunctionComponent } from 'react';
import Grid from '@mui/material/Grid';

import ScheduleContainer from '../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleSkeleton from '../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleSkeleton';
import ScheduleTitle from '../../../../../src/MotorsportTracker/Schedule/Ui/ScheduleTitle';

const PageSkeleton: FunctionComponent = () => (
    <ScheduleContainer>
        <ScheduleTitle />
        <Grid item container direction="row" justifyContent="center" sx={{ mb: 2 }}>
            <Grid item md={2} sm={4} xs={6}>
                <Skeleton variant="rectangular" animation="wave" height={32} sx={{ mb: 2 }} />
            </Grid>
        </Grid>
        <ScheduleSkeleton />
    </ScheduleContainer>
);

export default PageSkeleton;
