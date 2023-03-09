import React, { useState } from 'react';
import Collapse from '@mui/material/Collapse';
import Grid from '@mui/material/Grid';

import { MotorsportEvent } from '../../Shared/Types';
import ScheduleEventMainPanel from './ScheduleEventMainPanel';
import ScheduleEventTimetable from './ScheduleEventTimetable';

declare type ScheduleEventProps = {
    event: MotorsportEvent,
};

const ScheduleEvent: React.FunctionComponent<ScheduleEventProps> = ({ event }) => {
    const [showTimetable, setShowTimetable] = useState<boolean>(false);

    const toggleTimetable = () => setShowTimetable(!showTimetable);

    const handleWidth = 10;

    return (
        <Grid item container direction="column" sx={{ my: 1 }}>
            <ScheduleEventMainPanel toggleTimetable={toggleTimetable} handleWidth={handleWidth} event={event} />
            <Collapse in={showTimetable} timeout="auto" mountOnEnter unmountOnExit>
                <ScheduleEventTimetable leftHandleWidth={handleWidth} event={event} />
            </Collapse>
        </Grid>
    );
};

export default ScheduleEvent;
