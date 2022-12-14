import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarDayContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid item sx={{ p: 2, border: 1, borderColor: 'grey.700' }} xs={1}>
        {children}
    </Grid>
);

export default CalendarDayContainer;
