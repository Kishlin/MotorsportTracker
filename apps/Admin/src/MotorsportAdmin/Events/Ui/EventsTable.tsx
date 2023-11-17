'use client';

import {
    Paper,
    Table,
    TableRow,
    TableHead,
    TableBody,
    TableContainer,
} from '@mui/material';
import {
    useEffect,
    FunctionComponent, useState,
} from 'react';
import { styled } from '@mui/material/styles';
import Typography from '@mui/material/Typography';

import { Event } from '../../Shared/Types';
import eventsListApi from '../Api/EventsListApi';
import StyledTableCell from '../../../Shared/Ui/Table/StyledTableCell';
import EventClassificationButton from './EventClassificationButton';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

declare type EventsTableProps = {
    seriesName: string,
    year: number,
};

const EventsTable: FunctionComponent<EventsTableProps> = ({ seriesName, year }) => {
    const [events, setEvents] = useState<Event[]>([]);

    const refreshEvents = async () => {
        setEvents(await eventsListApi(seriesName, year));
    };

    useEffect(
        () => {
            refreshEvents();
        },
        [],
    );

    if (0 === events.length) {
        return (
            <Typography>There are no events at that time.</Typography>
        );
    }

    const content = events.map(
        (item: Event) => (
            <StyledTableRow key={item.id}>
                <StyledTableCell align="left">{item.name}</StyledTableCell>
                <StyledTableCell align="left">{item.sessions}</StyledTableCell>
                <StyledTableCell align="right">
                    <EventClassificationButton
                        onJobFinished={refreshEvents}
                        seriesName={seriesName}
                        year={year}
                        event={item.name}
                    />
                </StyledTableCell>
            </StyledTableRow>
        ),
    );

    return (
        <TableContainer component={Paper}>
            <Table aria-label="events-table">
                <TableHead>
                    <TableRow>
                        <StyledTableCell align="left">Event</StyledTableCell>
                        <StyledTableCell align="right">Sessions</StyledTableCell>
                        <StyledTableCell align="right">Actions</StyledTableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {content}
                </TableBody>
            </Table>
        </TableContainer>
    );
};

export default EventsTable;
