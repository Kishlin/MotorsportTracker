import TableCell, { tableCellClasses } from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import Typography from '@mui/material/Typography';
import TableBody from '@mui/material/TableBody';
import TableRow from '@mui/material/TableRow';
import Divider from '@mui/material/Divider';
import { FunctionComponent } from 'react';
import Table from '@mui/material/Table';
import Grid from '@mui/material/Grid';

import { MotorsportEvent, MotorsportSession } from '../../Shared/Types';

declare type ScheduleEventTimetableProps = {
    leftHandleWidth: number,
    event: MotorsportEvent,
};

const ScheduleEventTimetable: FunctionComponent<ScheduleEventTimetableProps> = ({ leftHandleWidth, event }) => {
    event.sessions.sort((a, b) => {
        if (null === a.start_date || null === b.start_date) {
            return 0;
        }

        return (new Date(a.start_date)).getTime() - (new Date(b.start_date)).getTime();
    });

    const timetable: { [key: string]: MotorsportSession[] } = {};
    const sessionsCount = event.sessions.length;

    for (let i = 0; i < sessionsCount; i += 1) {
        const session = event.sessions[i];

        let key: string = 'unknown';
        if (null !== session.start_date) {
            key = (new Date(session.start_date)).toLocaleDateString(undefined, { month: 'short', day: '2-digit', weekday: 'short' });
        }

        if (undefined === timetable[key]) {
            timetable[key] = [];
        }

        timetable[key] = [...timetable[key], session];
    }

    const timetableJSX = Object.keys(timetable).map((day: string) => (
        <Grid item xs={4} container direction="column" key={day} sx={{ my: 2, px: 2 }}>
            <Typography>{day}</Typography>
            <Divider sx={{ borderColor: event.series.color, mt: 1, mb: 2 }} />
            <TableContainer>
                <Table size="medium" sx={{ [`& .${tableCellClasses.root}`]: { borderBottom: 'none' } }}>
                    <TableBody>
                        {timetable[day].map((session: MotorsportSession) => {
                            const startDate = null !== session.start_date
                                ? new Date(session.start_date).toLocaleTimeString(undefined, {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                })
                                : <noscript />;

                            return (
                                <TableRow key={session.type + session.start_date}>
                                    <TableCell sx={{ p: 1 }}>{session.type}</TableCell>
                                    <TableCell sx={{ p: 1 }}>{startDate}</TableCell>
                                </TableRow>
                            );
                        })}
                    </TableBody>
                </Table>
            </TableContainer>
        </Grid>
    ));

    return (
        <Grid item container sx={{ mb: '-40px' }}>
            <Grid item xs={1} />
            <Grid item xs={11} sx={{ backgroundColor: 'divider', borderLeft: leftHandleWidth, borderColor: event.series.color }}>
                <Grid container direction="row">
                    {timetableJSX}
                </Grid>
            </Grid>
        </Grid>
    );
};

export default ScheduleEventTimetable;
