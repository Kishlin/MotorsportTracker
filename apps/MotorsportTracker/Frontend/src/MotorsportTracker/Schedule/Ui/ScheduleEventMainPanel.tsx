import React, { useEffect, useState } from 'react';
import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';

import ScheduleEventIconList from './ScheduleEventIconList';
import { MotorsportEvent } from '../../Shared/Types';

declare type ScheduleEventMainPanelProps = {
    toggleTimetable: () => void,
    handleWidth: number,
    event: MotorsportEvent,
};

const ScheduleEventMainPanel: React.FunctionComponent<ScheduleEventMainPanelProps> = ({
    toggleTimetable,
    handleWidth,
    event,
}) => {
    const [dateLabels, setDateLabels] = useState<{ start: string, end: string}>({ start: '', end: '' });

    useEffect(
        () => {
            let start = '';
            let end = '';

            if (null !== event.start_date) {
                const startDate = new Date(event.start_date);
                start = startDate.toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
            }

            if (null !== event.end_date) {
                const endDate = new Date(event.end_date);
                end = endDate.toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
            }

            setDateLabels({ start, end });
        },
        [],
    );

    const dateWidth = 100;

    return (
        <Grid item container direction="row" justifyContent="center">
            <Grid item xs={1}>
                <Grid container direction="column" justifyContent="center" sx={{ height: '100%', width: `${dateWidth}px` }}>
                    <Typography noWrap sx={{ maxWidth: `${dateWidth - 20}px` }}>{dateLabels.start}</Typography>
                    <Typography noWrap sx={{ maxWidth: `${dateWidth - 20}px` }}>{dateLabels.end}</Typography>
                </Grid>
            </Grid>
            <Grid
                item
                xs={11}
                sx={{
                    backgroundColor: 'background.paper',
                    borderTopLeftRadius: `${handleWidth}px`,
                    borderLeft: handleWidth,
                    borderColor: event.series.color,
                }}
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
                        <ScheduleEventIconList event={event} toggleTimetable={toggleTimetable} />
                    </Grid>
                </Grid>
            </Grid>
        </Grid>
    );
};

export default ScheduleEventMainPanel;
