import { Grid } from '@mui/material';
import React, { ReactNode, useEffect, useState } from 'react';

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
    const [scheduleJSX, setScheduleJSX] = useState<ReactNode>(<noscript />);

    useEffect(
        () => {
            const nextScheduleJSX = [];

            for (let day = new Date(firstDay); day <= lastDay; day.setDate(day.getDate() + 1)) {
                const scheduleKey = formatDateAsScheduleKey(day);

                if (undefined !== events[scheduleKey]) {
                    nextScheduleJSX.push((
                        <ScheduleDay
                            key={day.getTime()}
                            events={events[scheduleKey]}
                        />
                    ));
                }
            }

            setScheduleJSX(nextScheduleJSX);
        },
        [events],
    );

    return (
        <Grid item>
            <Grid container flexDirection="column" columns={{ xs: 1 }}>
                {scheduleJSX}
            </Grid>
        </Grid>
    );
};

export default ScheduleEventsList;
