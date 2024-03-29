'use client';

import TableRow from '@mui/material/TableRow';
import { styled } from '@mui/material/styles';
import { FunctionComponent } from 'react';

import StandingStandeeCell from '../../../Standing/Ui/Data/StandingStandeeCell';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import { TeamAnalytics } from '../../Types/Index';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

declare type TeamsAnalyticsRowProps = {
    analytics: TeamAnalytics,
};

const TeamsAnalyticsRow: FunctionComponent<TeamsAnalyticsRowProps> = ({ analytics }) => (
    <StyledTableRow>
        <StyledTableCell>{analytics.position.toString()}</StyledTableCell>
        <StandingStandeeCell name={analytics.name} country={analytics.country} />
        <StyledTableCell>{analytics.points.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.fastest_laps.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.finishes_one_and_two.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.podiums.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.poles.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.retirements.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.starts.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.top10s.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.top5s.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.wins.toString()}</StyledTableCell>
        <StyledTableCell>{analytics.wins_percentage.toString()}</StyledTableCell>
    </StyledTableRow>
);

export default TeamsAnalyticsRow;
