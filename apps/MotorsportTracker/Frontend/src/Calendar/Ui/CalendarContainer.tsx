import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <Grid container spacing={0} direction="column" sx={{ pt: 1 }}>
        {children}
    </Grid>
);

export default CalendarContainer;
