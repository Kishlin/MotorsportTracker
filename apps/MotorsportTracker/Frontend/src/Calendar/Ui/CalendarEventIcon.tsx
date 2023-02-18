import React from 'react';
import { Box } from '@mui/material';

const CalendarEventText: React.FunctionComponent<{ icon: string }> = ({ icon }) => (
    <Box sx={{ height: '15px', width: 'auto' }}>
        <img src={`/assets/championships/logos/${icon}`} alt={icon} height={10} />
    </Box>
);

export default CalendarEventText;
