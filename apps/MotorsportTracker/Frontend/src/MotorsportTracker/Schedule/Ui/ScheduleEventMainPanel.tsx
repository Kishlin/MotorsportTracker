import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';
import Grid from '@mui/material/Grid';

import ScheduleEventIconList from './ScheduleEventIconList';
import { MotorsportEvent } from '../../Shared/Types';

declare type ScheduleEventMainPanelProps = {
    toggleTimetable: () => void,
    event: MotorsportEvent,
    handleWidth: number,
};

const dateWidth = 100;

const ScheduleEventMainPanel: FunctionComponent<ScheduleEventMainPanelProps> = ({
    toggleTimetable,
    handleWidth,
    event,
}) => {
    const start = null !== event.start_date
        ? (new Date(event.start_date)).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })
        : '';

    const end = null !== event.end_date
        ? (new Date(event.end_date)).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })
        : '';

    const dateLabels = { start, end };

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
