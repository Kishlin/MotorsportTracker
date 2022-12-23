import React from 'react';

import CalendarEventContainer from '../Ui/CalendarEventContainer';
import CalendarEventText from '../Ui/CalendarEventText';
import { MotorsportEvent } from '../Types';

declare type CalendarEventProps = {
    event: MotorsportEvent,
};

const CalendarEvent: React.FunctionComponent<CalendarEventProps> = ({ event }) => (
    <CalendarEventContainer>
        <CalendarEventText>{event.date_time.substring(11, 16)}</CalendarEventText>
    </CalendarEventContainer>

);

export default CalendarEvent;
