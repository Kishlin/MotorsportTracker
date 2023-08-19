import { FunctionComponent } from 'react';
import Skeleton from '@mui/material/Skeleton';
import Grid from '@mui/material/Grid';

const ScheduleEventSkeleton: FunctionComponent = () => (
    <Grid item container direction="column" sx={{ my: 1 }}>
        <Grid item container direction="row" justifyContent="center">
            <Grid item xs={1} container direction="column" justifyContent="center">
                <Grid container direction="column" justifyContent="space-between" sx={{ height: '3.2rem' }}>
                    <Skeleton variant="rectangular" animation="wave" height="1.5rem" sx={{ mr: 1 }} />
                    <Skeleton variant="rectangular" animation="wave" height="1.5rem" sx={{ mr: 1 }} />
                </Grid>
            </Grid>
            <Grid item xs={11}>
                <Skeleton sx={{ borderTopLeftRadius: '10px' }} variant="rectangular" animation="wave" height={80} />
            </Grid>
        </Grid>
    </Grid>
);

export default ScheduleEventSkeleton;
