import React from 'react';

import formatDateForCalendar from '../Utils/Date/formatDateForCalendar';
import CalendarDayContainer from '../Ui/CalendarDayContainer';
import CalendarDayDateTitle from '../Ui/CalendarDayDateTitle';

declare type CalendarDayProps = {
    refMonth: number,
    day: Date,
};

const CalendarDay: React.FunctionComponent<CalendarDayProps> = ({ day, refMonth }) => {
    const isAnotherMonthThanRef = day.getMonth() !== refMonth;

    return (
        <CalendarDayContainer>
            <CalendarDayDateTitle isAnotherDayThanRefMonth={isAnotherMonthThanRef}>
                {formatDateForCalendar(day)}
            </CalendarDayDateTitle>
        </CalendarDayContainer>
    );
};

export default CalendarDay;
