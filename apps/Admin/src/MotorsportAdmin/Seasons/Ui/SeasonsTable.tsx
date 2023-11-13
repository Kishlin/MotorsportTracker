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

import { Season } from '../../Shared/Types';
import seasonsListApi from '../Api/SeasonsListApi';
import StyledTableCell from '../../../Shared/Ui/Table/StyledTableCell';
import SeasonCalendarButton from './SeasonCalendarButton';
import SeasonStandingsButton from './SeasonStandingsButton';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

declare type SeasonsTableProps = {
    seriesName: string,
};

const SeasonsTable: FunctionComponent<SeasonsTableProps> = ({ seriesName }) => {
    const [seasons, setSeasons] = useState<Season[]>([]);

    const refreshSeasons = async () => {
        setSeasons(await seasonsListApi(seriesName));
    };

    useEffect(
        () => {
            refreshSeasons();
        },
        [],
    );

    if (0 === seasons.length) {
        return (
            <Typography>There are no seasons at that time.</Typography>
        );
    }

    const content = seasons.map(
        (item: Season) => (
            <StyledTableRow key={item.id}>
                <StyledTableCell align="left">{item.year}</StyledTableCell>
                <StyledTableCell align="left">{item.events}</StyledTableCell>
                <StyledTableCell align="left">{item.standings}</StyledTableCell>
                <StyledTableCell align="right">
                    <SeasonCalendarButton onJobFinished={refreshSeasons} seriesName={seriesName} year={item.year} />
                    <SeasonStandingsButton onJobFinished={refreshSeasons} seriesName={seriesName} year={item.year} />
                </StyledTableCell>
            </StyledTableRow>
        ),
    );

    return (
        <TableContainer component={Paper}>
            <Table aria-label="seasons-table">
                <TableHead>
                    <TableRow>
                        <StyledTableCell align="left">Year</StyledTableCell>
                        <StyledTableCell align="right">Events</StyledTableCell>
                        <StyledTableCell align="right">Standings</StyledTableCell>
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

export default SeasonsTable;
