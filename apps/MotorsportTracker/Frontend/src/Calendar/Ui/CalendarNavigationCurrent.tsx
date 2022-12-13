import { Typography } from '@mui/material';
import React from 'react';

const CalendarNavigationCurrent: React.FunctionComponent<{ children: string }> = ({ children }) => (
    <Typography align="center">{children}</Typography>
);

export default CalendarNavigationCurrent;
