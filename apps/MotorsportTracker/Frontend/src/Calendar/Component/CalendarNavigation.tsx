import React from 'react';

import CalendarNavigationContainer from '../Ui/CalendarNavigationContainer';
import CalendarNavigationPrevious from '../Ui/CalendarNavigationPrevious';
import CalendarNavigationCurrent from '../Ui/CalendarNavigationCurrent';
import CalendarNavigationNext from '../Ui/CalendarNavigationNext';

import ucFirst from '../Utils/String/ucFirst';

declare type CalendarNavigationProps = {
    month: string,
    year: number,
}

const CalendarNavigation: React.FunctionComponent<CalendarNavigationProps> = ({ month, year }) => (
    <CalendarNavigationContainer
        previous={<CalendarNavigationPrevious />}
        current={<CalendarNavigationCurrent>{`${ucFirst(month)} - ${year}`}</CalendarNavigationCurrent>}
        next={<CalendarNavigationNext />}
    />
);

export default CalendarNavigation;
