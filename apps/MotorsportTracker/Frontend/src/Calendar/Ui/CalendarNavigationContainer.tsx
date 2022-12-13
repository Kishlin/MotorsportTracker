import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

declare type CalendarNavigationContainerProps = {
    previous: ReactNode,
    current: ReactNode,
    next: ReactNode,
}

const CalendarNavigationContainer: React.FunctionComponent<CalendarNavigationContainerProps> = ({
    previous,
    current,
    next,
}) => (
    <Grid item container direction="row">
        <Grid item md={5} sm={4} xs={3} container justifyContent="flex-end">
            { previous }
        </Grid>
        <Grid item md={2} sm={4} xs={6}>
            { current }
        </Grid>
        <Grid item md={5} sm={4} xs={3}>
            { next }
        </Grid>
    </Grid>
);

export default CalendarNavigationContainer;
