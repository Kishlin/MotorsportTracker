import React from 'react';

import CalendarContainer from '../Ui/CalendarContainer';

import CalendarNavigation from './CalendarNavigation';
import CalendarWeekHeader from './CalendarWeekHeader';
import CalendarBody from './CalendarBody';

declare type CalendarProps = {
    date: Date,
}

const Calendar: React.FunctionComponent<CalendarProps> = ({ date }) => (
    <CalendarContainer>
        <CalendarNavigation date={date} />
        <CalendarWeekHeader />
        <CalendarBody date={date} />
    </CalendarContainer>
);

export default Calendar;
