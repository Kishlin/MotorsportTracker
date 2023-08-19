'use client';

import { FunctionComponent, ReactElement } from 'react';
import Typography from '@mui/material/Typography';
import Divider from '@mui/material/Divider';
import Grid from '@mui/material/Grid';

import formatDateAsScheduleKey from '../Utils/Date/formatDateAsScheduleKey';
import { EventsSchedule } from '../../Shared/Types';
import ScheduleDay from './ScheduleDay';

declare type ScheduleEventsListProps = {
    events: EventsSchedule,
}

const NO_DATE_KEY = 'no-date';

const computeScheduleJSX = (events: EventsSchedule): ReactElement[] => {
    const nextScheduleJSX = [];
    const now = new Date();

    const lastKey = Object.keys(events)[Object.keys(events).length - 1];
    const lastDayString = NO_DATE_KEY === lastKey
        ? Object.keys(events)[Object.keys(events).length - 2]
        : lastKey;

    const firstDay = new Date(Object.keys(events)[0]);
    const lastDay = new Date(lastDayString);

    for (let day = firstDay; day <= lastDay; day.setDate(day.getDate() + 1)) {
        if (now.toLocaleDateString() === day.toLocaleDateString()) {
            nextScheduleJSX.push((
                <Divider key="divider" textAlign="center" sx={{ color: '#d95757' }}>Today</Divider>
            ));
        }

        const scheduleKey = formatDateAsScheduleKey(day) as `${string}-${string}-${string}`;

        if (undefined !== events[scheduleKey]) {
            nextScheduleJSX.push((
                <ScheduleDay key={day.getTime()} events={events[scheduleKey]} />
            ));
        }
    }

    if (undefined !== events[NO_DATE_KEY]) {
        nextScheduleJSX.push((
            <ScheduleDay key="no-date" events={events[NO_DATE_KEY]} />
        ));
    }

    return nextScheduleJSX;
};

const ScheduleEventsList: FunctionComponent<ScheduleEventsListProps> = ({ events }) => {
    if (0 === Object.keys(events).length) {
        return <Typography align="center">No events available at this time.</Typography>;
    }

    const nextScheduleJSX = computeScheduleJSX(events);

    return (
        <Grid item container flexDirection="column" columns={{ xs: 1 }}>
            {nextScheduleJSX}
        </Grid>
    );
};

export default ScheduleEventsList;
