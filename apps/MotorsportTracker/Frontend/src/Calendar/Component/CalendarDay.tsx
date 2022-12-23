import React, { useEffect, useState } from 'react';

import formatDateForCalendar from '../Utils/Date/formatDateForCalendar';
import CalendarDayContainer from '../Ui/CalendarDayContainer';
import CalendarDayDateTitle from '../Ui/CalendarDayDateTitle';
import { EventCalendarDay, MotorsportEvent } from '../Types';
import CalendarEvent from './CalendarEvent';
import CalendarDayDateEvents from '../Ui/CalendarDayDateEvents';

declare type CalendarDayProps = {
    events: EventCalendarDay,
    refMonth: number,
    day: Date,
};

const CalendarDay: React.FunctionComponent<CalendarDayProps> = ({ day, refMonth, events }) => {
    const isAnotherMonthThanRef = day.getMonth() !== refMonth;

    const [hydrated, setHydrated] = useState<boolean>(false);

    useEffect(() => setHydrated(true), []);

    return (
        <CalendarDayContainer>
            <CalendarDayDateTitle isAnotherDayThanRefMonth={isAnotherMonthThanRef}>
                {formatDateForCalendar(day)}
            </CalendarDayDateTitle>
            <CalendarDayDateEvents>
                {
                    hydrated
                        ? Object.values(events).map(
                            (value: MotorsportEvent) => (<CalendarEvent key={value.date_time} event={value} />),
                        )
                        : <noscript />
                }
            </CalendarDayDateEvents>
        </CalendarDayContainer>
    );
};

export default CalendarDay;
