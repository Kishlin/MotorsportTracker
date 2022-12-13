import { Grid } from '@mui/material';
import React from 'react';

import firstSundayAfterEndOfMonthDate from '../Utils/Date/firstSundayAfterEndOfMonthDate';
import firstMondayBeforeOrAtDate from '../Utils/Date/firstMondayBeforeOrAtDate';
import CalendarDay from './CalendarDay';

declare type CalendarBodyProps = {
    month: string,
    year: number,
}

const CalendarBody: React.FunctionComponent<CalendarBodyProps> = ({ month, year }) => {
    const firstDayOfRequestedMonth = new Date(Date.parse(`${month} 1, ${year}`));
    const firstDayOfCalendar = firstMondayBeforeOrAtDate(firstDayOfRequestedMonth);
    const lastDayOfCalendar = firstSundayAfterEndOfMonthDate(firstDayOfRequestedMonth);

    const calendar = [];

    for (let day = firstDayOfCalendar; day <= lastDayOfCalendar; day.setDate(day.getDate() + 1)) {
        calendar.push((
            <CalendarDay
                refMonth={month}
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
