import React from 'react';

import CalendarNavigationContainer from '../Ui/CalendarNavigationContainer';
import CalendarNavigationPrevious from '../Ui/CalendarNavigationPrevious';
import CalendarNavigationCurrent from '../Ui/CalendarNavigationCurrent';
import computeCalendarUri from '../Utils/Navigation/computeCalendarUri';
import formatDateForHeader from '../Utils/Date/formatDateForHeader';
import CalendarNavigationNext from '../Ui/CalendarNavigationNext';

import Link from '../../Shared/Ui/Navigation/Link';

import addMonths from '../Utils/Date/addMonths';

declare type CalendarNavigationProps = {
    date: Date,
}

const CalendarNavigation: React.FunctionComponent<CalendarNavigationProps> = ({ date }) => (
    <CalendarNavigationContainer
        previous={(
            <Link to={computeCalendarUri(addMonths(date, -1))}>
                <CalendarNavigationPrevious />
            </Link>
        )}
        current={(
            <CalendarNavigationCurrent>
                {formatDateForHeader(date)}
            </CalendarNavigationCurrent>
        )}
        next={(
            <Link to={computeCalendarUri(addMonths(date, +1))}>
                <CalendarNavigationNext />
            </Link>
        )}
    />
);

export default CalendarNavigation;
