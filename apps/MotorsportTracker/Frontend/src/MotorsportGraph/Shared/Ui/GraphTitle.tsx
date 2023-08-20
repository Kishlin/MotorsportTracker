'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

import { SeasonEvent } from '../../../MotorsportTracker/Shared/Types';

declare type GraphTitleProps = {
    event: SeasonEvent,
};

const GraphTitle: FunctionComponent<GraphTitleProps> = ({ event }) => (
    <Typography variant="h4" align="left" sx={{ my: 4 }}>
        {`${event.name} - Graphs`}
    </Typography>
);

export default GraphTitle;
