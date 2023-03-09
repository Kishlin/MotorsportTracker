import React from 'react';
import { Grid } from '@mui/material';

import { MotorsportEvent } from '../../Shared/Types';
import ScheduleEvent from './ScheduleEvent';

declare type ScheduleDayProps = {
    events: MotorsportEvent[],
};

const ScheduleDay: React.FunctionComponent<ScheduleDayProps> = ({ events }) => {
    const eventsJSX = Object.values(events).map(
        (event: MotorsportEvent) => <ScheduleEvent key={event.id} event={event} />,
    );

    return (
        <Grid item container flexDirection="column" sx={{ my: 1 }}>
            {eventsJSX}
        </Grid>
    );
};

export default ScheduleDay;
