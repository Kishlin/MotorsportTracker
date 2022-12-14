import { Grid } from '@mui/material';
import React from 'react';

import firstSundayAfterEndOfMonthDate from '../Utils/Date/firstSundayAfterEndOfMonthDate';
import firstMondayBeforeOrAtDate from '../Utils/Date/firstMondayBeforeOrAtDate';
import CalendarDay from './CalendarDay';

declare type CalendarBodyProps = {
    date: Date,
}

const CalendarBody: React.FunctionComponent<CalendarBodyProps> = ({ date }) => {
    const lastDayOfCalendar = firstSundayAfterEndOfMonthDate(date);
    const firstDayOfCalendar = firstMondayBeforeOrAtDate(date);

    const calendar = [];

    for (let day = firstDayOfCalendar; day <= lastDayOfCalendar; day.setDate(day.getDate() + 1)) {
        calendar.push((
            <CalendarDay
                refMonth={date.getMonth()}
                key={day.getTime()}
                day={new Date(day.getTime())}
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
