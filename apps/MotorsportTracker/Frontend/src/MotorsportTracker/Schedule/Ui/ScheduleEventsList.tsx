import { Grid } from '@mui/material';
import React from 'react';

import { EventsSchedule } from '../../Shared/Types';
import formatDateAsScheduleKey from '../Utils/Date/formatDateAsScheduleKey';
import ScheduleDay from './ScheduleDay';

declare type ScheduleEventsListProps = {
    events: EventsSchedule,
    firstDay: Date,
    lastDay: Date,
}

const ScheduleEventsList: React.FunctionComponent<ScheduleEventsListProps> = ({
    events,
    firstDay,
    lastDay,
}) => {
    const schedule = [];

    for (let day = firstDay; day <= lastDay; day.setDate(day.getDate() + 1)) {
        const scheduleKey = formatDateAsScheduleKey(day);

        if (undefined !== events[scheduleKey]) {
            schedule.push((
                <ScheduleDay
                    key={day.getTime()}
                    events={events[scheduleKey]}
                />
            ));
        }
    }

    return (
        <Grid item>
            <Grid container flexDirection="column" columns={{ xs: 1 }}>
                {schedule}
            </Grid>
        </Grid>
    );
};

export default ScheduleEventsList;
