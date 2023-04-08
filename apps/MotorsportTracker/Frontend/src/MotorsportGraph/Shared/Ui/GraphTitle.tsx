import Typography from '@mui/material/Typography';
import React from 'react';

import { SeasonEvent } from '../../../MotorsportTracker/Shared/Types';

declare type GraphTitleProps = {
    event: SeasonEvent,
};

const GraphTitle: React.FunctionComponent<GraphTitleProps> = ({ event }) => (
    <Typography variant="h4" align="left" sx={{ my: 4 }}>
        {`${event.name} - Graphs`}
    </Typography>
);

export default GraphTitle;
