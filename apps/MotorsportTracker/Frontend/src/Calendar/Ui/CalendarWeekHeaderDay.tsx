import { Grid, Typography } from '@mui/material';
import React from 'react';

const CalendarWeekHeaderDay: React.FunctionComponent<{ children: string }> = ({ children }) => (
    <Grid item xs={1}>
        <Typography align="center">{children}</Typography>
    </Grid>
);

export default CalendarWeekHeaderDay;
