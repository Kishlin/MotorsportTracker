import SlowMotionVideoIcon from '@mui/icons-material/SlowMotionVideo';
import LeaderboardIcon from '@mui/icons-material/Leaderboard';
import { SvgIconProps } from '@mui/material/SvgIcon/SvgIcon';
import QueryStatsIcon from '@mui/icons-material/QueryStats';
import ScheduleIcon from '@mui/icons-material/Schedule';
import Typography from '@mui/material/Typography';
import Tooltip from '@mui/material/Tooltip';
import React from 'react';

import useNavigate from '../../../Shared/Hooks/useNavigate';
import { MotorsportEvent } from '../../Shared/Types';

declare type ScheduleEventIconListProps = {
    toggleTimetable: () => void,
    event: MotorsportEvent,
};

const ScheduleEventIconList: React.FunctionComponent<ScheduleEventIconListProps> = ({ event, toggleTimetable }) => {
    if (null !== event.status) {
        return (
            <Typography sx={{ color: '#d95757' }}>{event.status}</Typography>
        );
    }

    const iconProps: Partial<SvgIconProps> = 0 === event.sessions.length
        ? { color: 'disabled', sx: { cursor: 'not-allowed' } }
        : { color: 'action', sx: { cursor: 'pointer' } };

    const timetableIcon = (
        <Tooltip title="Timetable">
            <ScheduleIcon onClick={toggleTimetable} {...iconProps} />
        </Tooltip>
    );

    if (null === event.start_date || new Date(event.start_date) > new Date()) {
        return timetableIcon;
    }

    const { redirectionTo } = useNavigate();

    const eventUri = event.slug.replaceAll('_', '/');

    const props: Partial<SvgIconProps> = { color: 'action', sx: { cursor: 'pointer', mr: 1 } };

    return (
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
    );
};

export default ScheduleEventIconList;
