import React from 'react';

import CalendarEventContainer from '../Ui/CalendarEventContainer';
import CalendarEventText from '../Ui/CalendarEventText';
import { MotorsportEvent } from '../Types';
import CalendarEventIcon from '../Ui/CalendarEventIcon';

declare type CalendarEventProps = {
    event: MotorsportEvent,
};

const CalendarEvent: React.FunctionComponent<CalendarEventProps> = ({ event }) => (
    <CalendarEventContainer color={event.color}>
        <CalendarEventIcon icon={event.icon} />
        <CalendarEventText>{event.name}</CalendarEventText>
    </CalendarEventContainer>

);

export default CalendarEvent;
