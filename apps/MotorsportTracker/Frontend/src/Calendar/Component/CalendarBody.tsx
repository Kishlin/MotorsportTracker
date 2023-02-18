import { Grid } from '@mui/material';
import React from 'react';

import formatDateAsCalendarKey from '../Utils/Date/formatDateAsCalendarKey';
import { EventCalendarMonth } from '../Types';
import CalendarDay from './CalendarDay';

declare type CalendarBodyProps = {
    events: EventCalendarMonth,
    firstDay: Date,
    lastDay: Date,
    date: Date,
}

const CalendarBody: React.FunctionComponent<CalendarBodyProps> = ({
    events,
    firstDay,
    lastDay,
    date,
}) => {
    const calendar = [];

    for (let day = firstDay; day <= lastDay; day.setDate(day.getDate() + 1)) {
        const calendarKey = formatDateAsCalendarKey(day);

        calendar.push((
            <CalendarDay
                key={day.getTime()}
                refMonth={date.getMonth()}
                day={new Date(day.getTime())}
                events={undefined === events[calendarKey] ? {} : events[calendarKey]}
            />
        ));
    }

    return (
        <Grid item container columns={{ xs: 7 }} flexGrow={2}>
            {calendar}
        </Grid>
    );
};

export default CalendarBody;
