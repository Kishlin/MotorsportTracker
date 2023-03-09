import ScheduleIcon from '@mui/icons-material/Schedule';
import { Grid, Typography } from '@mui/material';
import React, { useEffect, useState } from 'react';

import { MotorsportEvent } from '../../Shared/Types';

declare type ScheduleEventProps = {
    event: MotorsportEvent,
};

const ScheduleEvent: React.FunctionComponent<ScheduleEventProps> = ({ event }) => {
    const [dateLabels, setDateLabels] = useState<{ start: string, end: string}>({ start: '', end: '' });

    useEffect(
        () => {
            const startDate = new Date(event.start_date);
            const endDate = new Date(event.end_date);

            setDateLabels({
                start: startDate.toLocaleDateString(undefined, { day: 'numeric', month: 'short' }),
                end: endDate.toLocaleDateString(undefined, { day: 'numeric', month: 'short' }),
            });
        },
        [],
    );

    const leftHandleWidth = 10;
    const dateWidth = 100;

    const iconProps: { color: 'action'|'disabled', sx: { cursor: 'pointer'|'not-allowed' } } = 0 === event.sessions.length
        ? { color: 'disabled', sx: { cursor: 'not-allowed' } }
        : { color: 'action', sx: { cursor: 'pointer' } };

    return (
        <Grid item container direction="row" sx={{ my: 1 }} justifyContent="center">
            <Grid item>
                <Grid container direction="column" justifyContent="center" sx={{ height: '100%', width: `${dateWidth}px` }}>
                    <Typography noWrap sx={{ maxWidth: `${dateWidth - 20}px` }}>{dateLabels.start}</Typography>
                    <Typography noWrap sx={{ maxWidth: `${dateWidth - 20}px` }}>{dateLabels.end}</Typography>
                </Grid>
            </Grid>
            <Grid
                item
                sx={{
                    backgroundColor: 'background.paper',
                    borderBottomLeftRadius: `${leftHandleWidth}px`,
                    borderTopLeftRadius: `${leftHandleWidth}px`,
                    borderLeft: leftHandleWidth,
                    borderColor: event.series.color,
                }}
                flexGrow={2}
            >
                <Grid container direction="row" alignItems="center" sx={{ py: 2 }}>
                    <Grid item sx={{ ml: 4 }}>
                        <img style={{ display: 'block' }} src={`/assets/championships/logos/${event.series.icon}`} alt={event.series.slug} />
                    </Grid>
                    <Grid item sx={{ ml: 4 }} flexGrow={2}>
                        <Grid container direction="column">
                            <Typography noWrap>{event.name}</Typography>
                            <Typography noWrap>{event.venue.name}</Typography>
                        </Grid>
                    </Grid>
                    <Grid item sx={{ height: '24px', mx: 4 }}>
                        <ScheduleIcon {...iconProps} />
                    </Grid>
                </Grid>
            </Grid>
        </Grid>
    );
};

export default ScheduleEvent;
