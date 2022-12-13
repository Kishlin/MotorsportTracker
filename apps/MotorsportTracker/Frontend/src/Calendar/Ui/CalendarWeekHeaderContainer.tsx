import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarWeekHeaderContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid item container columns={{ xs: 7 }}>
        {children}
    </Grid>
);

export default CalendarWeekHeaderContainer;
