import React from 'react';

import CalendarContainer from '../Ui/CalendarContainer';

import CalendarNavigation from './CalendarNavigation';
import CalendarWeekHeader from './CalendarWeekHeader';
import CalendarBody from './CalendarBody';

import { EventCalendarMonth } from '../Types';

declare type CalendarProps = {
    events: EventCalendarMonth,
    date: Date,
}

const Calendar: React.FunctionComponent<CalendarProps> = ({ date, events }) => (
    <CalendarContainer>
        <CalendarNavigation date={date} />
        <CalendarWeekHeader />
        <CalendarBody date={date} events={events} />
    </CalendarContainer>
);

export default Calendar;
