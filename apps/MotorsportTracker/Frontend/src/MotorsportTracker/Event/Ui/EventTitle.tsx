import React from 'react';
import Typography from '@mui/material/Typography';

import { SeasonEvent } from '../../Shared/Types';

declare type EventTitleProps = {
    event: SeasonEvent,
    page: string,
};

const EventTitle: React.FunctionComponent<EventTitleProps> = ({ event, page }) => (
    <Typography variant="h4" align="left" sx={{ my: 4 }}>
        {`${event.name} - ${page.charAt(0).toUpperCase()}${page.slice(1)}`}
    </Typography>
);

export default EventTitle;
