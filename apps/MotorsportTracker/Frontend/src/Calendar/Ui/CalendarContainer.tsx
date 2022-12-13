import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container spacing={2} direction="column">
        {children}
    </Grid>
);

export default CalendarContainer;
