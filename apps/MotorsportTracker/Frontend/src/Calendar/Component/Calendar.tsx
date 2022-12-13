import React from 'react';

import CalendarContainer from '../Ui/CalendarContainer';

import CalendarNavigation from './CalendarNavigation';
import CalendarWeekHeader from './CalendarWeekHeader';
import CalendarBody from './CalendarBody';

declare type CalendarProps = {
    month: string,
    year: number,
}

const Calendar: React.FunctionComponent<CalendarProps> = ({ month, year }) => (
    <CalendarContainer>
        <CalendarNavigation month={month} year={year} />
        <CalendarWeekHeader />
        <CalendarBody month={month} year={year} />
    </CalendarContainer>
);

export default Calendar;
