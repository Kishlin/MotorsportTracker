import React from 'react';

import CalendarContainer from '../Ui/CalendarContainer';

import CalendarNavigation from './CalendarNavigation';
import CalendarWeekHeader from './CalendarWeekHeader';
import CalendarBody from './CalendarBody';

import { EventCalendarMonth } from '../Types';

declare type CalendarProps = {
    events: EventCalendarMonth,
    firstDay: Date,
    lastDay: Date,
    date: Date,
}

const Calendar: React.FunctionComponent<CalendarProps> = ({
    events,
    firstDay,
    lastDay,
    date,
}) => (
    <CalendarContainer>
        <CalendarNavigation date={date} />
        <CalendarWeekHeader />
        <CalendarBody firstDay={firstDay} lastDay={lastDay} date={date} events={events} />
    </CalendarContainer>
);

export default Calendar;
