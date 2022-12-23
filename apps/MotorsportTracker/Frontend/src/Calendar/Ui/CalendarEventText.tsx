import { Typography } from '@mui/material';
import React from 'react';

const CalendarEventText: React.FunctionComponent<{ children: string }> = ({ children }) => (
    <Typography>{children}</Typography>
);

export default CalendarEventText;
