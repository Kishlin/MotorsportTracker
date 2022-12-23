import React, { useContext } from 'react';

import { HydrationContext, HydrationContextType } from '../../Shared/Contexts/HydrationContext';
import formatDateForCalendar from '../Utils/Date/formatDateForCalendar';
import CalendarDayDateEvents from '../Ui/CalendarDayDateEvents';
import CalendarDayContainer from '../Ui/CalendarDayContainer';
import CalendarDayDateTitle from '../Ui/CalendarDayDateTitle';
import { EventCalendarDay, MotorsportEvent } from '../Types';
import CalendarEvent from './CalendarEvent';

declare type CalendarDayProps = {
    events: EventCalendarDay,
    refMonth: number,
    day: Date,
};

const CalendarDay: React.FunctionComponent<CalendarDayProps> = ({ day, refMonth, events }) => {
    const ifHydrated = useContext<HydrationContextType>(HydrationContext);

    const eventsJSX = ifHydrated(
        Object.values(events).map(
            (value: MotorsportEvent) => (<CalendarEvent key={value.date_time} event={value} />),
        ),
    );

    return (
        <CalendarDayContainer>
            <CalendarDayDateTitle isAnotherDayThanRefMonth={day.getMonth() !== refMonth}>
                {formatDateForCalendar(day)}
            </CalendarDayDateTitle>
            <CalendarDayDateEvents>
                {eventsJSX}
            </CalendarDayDateEvents>
        </CalendarDayContainer>
    );
};

export default CalendarDay;
