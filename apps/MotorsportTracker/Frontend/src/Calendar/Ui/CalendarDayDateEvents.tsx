import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarDayDateEvents: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container direction="column">
        {children}
    </Grid>
);

export default CalendarDayDateEvents;
