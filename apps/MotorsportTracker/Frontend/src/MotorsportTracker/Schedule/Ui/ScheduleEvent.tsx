import { faQuestion } from '@fortawesome/free-solid-svg-icons/faQuestion';
import { Grid, Typography } from '@mui/material';
import React, { useEffect, useState } from 'react';

import { MotorsportEvent } from '../../Shared/Types';
import FontAwesomeSvgIcon from '../../../Shared/Ui/Icon/FontAwesomeSvgIcon';

declare type ScheduleEventProps = {
    event: MotorsportEvent,
};

const ScheduleEvent: React.FunctionComponent<ScheduleEventProps> = ({ event }) => {
    const [dateLabels, setDateLabels] = useState<{ date: string, time: string}>({ date: '', time: '' });

    const leftHandleWidth = 10;
    const dateWidth = 100;

    const eventDate = new Date(event.date_time);

    useEffect(
        () => {
            setDateLabels({
                date: eventDate.toLocaleDateString(undefined, { day: 'numeric', month: 'short' }),
                time: eventDate.toLocaleTimeString(undefined, { hour: 'numeric', minute: 'numeric' }),
            });
        },
        [],
    );

    return (
        <Grid item container direction="row" sx={{ my: 1 }} justifyContent="center">
            <Grid item>
                <Grid container direction="column" justifyContent="center" sx={{ height: '100%', width: `${dateWidth}px` }}>
                    <Typography noWrap sx={{ maxWidth: `${dateWidth - 20}px` }}>{dateLabels.date}</Typography>
                    <Typography noWrap sx={{ maxWidth: `${dateWidth - 20}px` }}>{dateLabels.time}</Typography>
                </Grid>
            </Grid>
            <Grid
                item
                sx={{
                    backgroundColor: 'background.paper',
                    borderBottomLeftRadius: `${leftHandleWidth}px`,
                    borderTopLeftRadius: `${leftHandleWidth}px`,
                    borderLeft: leftHandleWidth,
                    borderColor: event.color,
                }}
                flexGrow={2}
            >
                <Grid container direction="row" alignItems="center" sx={{ py: 2 }}>
                    <Grid item sx={{ ml: 4 }}>
                        <img style={{ display: 'block' }} src={`/assets/championships/logos/${event.icon}`} alt={event.championship_slug} />
                    </Grid>
                    <Grid item sx={{ ml: 4 }} flexGrow={2}>
                        <Grid container direction="column">
                            <Typography noWrap>{event.name}</Typography>
                            <Typography noWrap>{event.type}</Typography>
                        </Grid>
                    </Grid>
                    <Grid item sx={{ height: '24px', mx: 4, cursor: 'pointer' }}>
                        <FontAwesomeSvgIcon icon={faQuestion} />
                    </Grid>
                </Grid>
            </Grid>
        </Grid>
    );
};

export default ScheduleEvent;
