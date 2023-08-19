import { FunctionComponent } from 'react';
import Grid from '@mui/material/Grid';

import { MotorsportEvent } from '../../Shared/Types';
import ScheduleEvent from './ScheduleEvent';

declare type ScheduleDayProps = {
    events: MotorsportEvent[],
};

const ScheduleDay: FunctionComponent<ScheduleDayProps> = ({ events }) => {
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
