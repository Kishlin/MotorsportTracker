import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarDayContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid item sx={{ p: 2 }} xs={1}>
        {children}
    </Grid>
);

export default CalendarDayContainer;
