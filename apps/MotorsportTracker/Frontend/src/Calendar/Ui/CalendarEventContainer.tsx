import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

const CalendarEventContainer: React.FunctionComponent<{ children: ReactNode }> = ({ children }) => {
    const sx = {
        backgroundColor: 'red',
        borderRadius: '3px',
        mt: '3px',
        p: '1px',
    };

    return (
        <Grid item container direction="row" sx={sx}>
            { children }
        </Grid>
    );
};

export default CalendarEventContainer;
