import React, { useEffect, useState } from 'react';
import Grid from '@mui/material/Grid';
import Tooltip from '@mui/material/Tooltip';
import Typography from '@mui/material/Typography';
import ScheduleIcon from '@mui/icons-material/Schedule';
import QueryStatsIcon from '@mui/icons-material/QueryStats';
import LeaderboardIcon from '@mui/icons-material/Leaderboard';
import SlowMotionVideoIcon from '@mui/icons-material/SlowMotionVideo';
import { SvgIconProps } from '@mui/material/SvgIcon/SvgIcon';

import { MotorsportEvent } from '../../Shared/Types';
import useNavigate from '../../../Shared/Hooks/useNavigate';

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
    const { redirectionTo } = useNavigate();

    const [dateLabels, setDateLabels] = useState<{ start: string, end: string}>({ start: '', end: '' });
    const [eventIcons, setEventIcons] = useState<React.ReactNode>(<noscript />);

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

            if (null !== event.status) {
                setEventIcons((
                    <Typography sx={{ color: '#d95757' }}>{event.status}</Typography>
                ));

                return;
            }

            const iconProps: Partial<SvgIconProps> = 0 === event.sessions.length
                ? { color: 'disabled', sx: { cursor: 'not-allowed' } }
                : { color: 'action', sx: { cursor: 'pointer' } };

            const timetableIcon = (
                <Tooltip title="Timetable">
                    <ScheduleIcon onClick={toggleTimetable} {...iconProps} />
                </Tooltip>
            );

            if (null !== event.start_date && new Date(event.start_date) <= new Date()) {
                const eventUri = event.slug.replaceAll('_', '/');

                const props: Partial<SvgIconProps> = { color: 'action', sx: { cursor: 'pointer', mr: 1 } };

                setEventIcons((
                    <>
                        <Tooltip title="Results">
                            <LeaderboardIcon onClick={redirectionTo(`/event/${eventUri}/results`)} {...props} />
                        </Tooltip>
                        <Tooltip title="Histories">
                            <SlowMotionVideoIcon onClick={redirectionTo(`/event/${eventUri}/histories`)} {...props} />
                        </Tooltip>
                        <Tooltip title="Graphs">
                            <QueryStatsIcon onClick={redirectionTo(`/event/${eventUri}/graphs`)} {...props} />
                        </Tooltip>
                        {timetableIcon}
                    </>
                ));
            } else {
                setEventIcons(timetableIcon);
            }
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
                        {eventIcons}
                    </Grid>
                </Grid>
            </Grid>
        </Grid>
    );
};

export default ScheduleEventMainPanel;
