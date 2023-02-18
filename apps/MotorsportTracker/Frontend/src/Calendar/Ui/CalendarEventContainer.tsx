import React, { ReactNode } from 'react';
import { Grid } from '@mui/material';

declare type Created = { children: ReactNode, color: string };

const CalendarEventContainer: React.FunctionComponent<Created> = ({ children, color }) => {
    const sx = {
        backgroundColor: color,
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
