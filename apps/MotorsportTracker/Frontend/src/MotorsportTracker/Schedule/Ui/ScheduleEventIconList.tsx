import SlowMotionVideoIcon from '@mui/icons-material/SlowMotionVideo';
import LeaderboardIcon from '@mui/icons-material/Leaderboard';
import { SvgIconProps } from '@mui/material/SvgIcon/SvgIcon';
import QueryStatsIcon from '@mui/icons-material/QueryStats';
import ScheduleIcon from '@mui/icons-material/Schedule';
import Typography from '@mui/material/Typography';
import Tooltip from '@mui/material/Tooltip';
import { FunctionComponent } from 'react';
import Link from 'next/link';

import { MotorsportEvent } from '../../Shared/Types';

declare type ScheduleEventIconListProps = {
    toggleTimetable: () => void,
    event: MotorsportEvent,
};

const ScheduleEventIconList: FunctionComponent<ScheduleEventIconListProps> = ({ event, toggleTimetable }) => {
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

    const slugParts = event.slug.split('_');
    slugParts.splice(2, 1);

    const eventUri = slugParts.join('/');

    const props: Partial<SvgIconProps> = { color: 'action', sx: { cursor: 'pointer', mr: 1 } };

    return (
        <>
            <Tooltip title="Results">
                <Link href={`/event/${eventUri}/results`}>
                    <LeaderboardIcon {...props} />
                </Link>
            </Tooltip>
            <Tooltip title="Histories">
                <Link href={`/event/${eventUri}/histories`}>
                    <SlowMotionVideoIcon {...props} />
                </Link>
            </Tooltip>
            <Tooltip title="Graphs">
                <Link href={`/event/${eventUri}/graphs`}>
                    <QueryStatsIcon {...props} />
                </Link>
            </Tooltip>
            {timetableIcon}
        </>
    );
};

export default ScheduleEventIconList;
