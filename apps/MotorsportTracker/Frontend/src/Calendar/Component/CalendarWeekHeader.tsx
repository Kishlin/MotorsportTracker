import React from 'react';

import CalendarWeekHeaderContainer from '../Ui/CalendarWeekHeaderContainer';
import CalendarWeekHeaderDay from '../Ui/CalendarWeekHeaderDay';

const CalendarWeekHeader: React.FunctionComponent = () => (
    <CalendarWeekHeaderContainer>
        <CalendarWeekHeaderDay>Mon</CalendarWeekHeaderDay>
        <CalendarWeekHeaderDay>Tue</CalendarWeekHeaderDay>
        <CalendarWeekHeaderDay>Wed</CalendarWeekHeaderDay>
        <CalendarWeekHeaderDay>Thu</CalendarWeekHeaderDay>
        <CalendarWeekHeaderDay>Fri</CalendarWeekHeaderDay>
        <CalendarWeekHeaderDay>Sat</CalendarWeekHeaderDay>
        <CalendarWeekHeaderDay>Sun</CalendarWeekHeaderDay>
    </CalendarWeekHeaderContainer>
);

export default CalendarWeekHeader;
