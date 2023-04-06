import TableRow from '@mui/material/TableRow';
import { styled } from '@mui/material/styles';
import React from 'react';

import SessionTableCell from './SessionTableCell';
import { Result } from '../../Types/Index';
import ResultTimeCell from '../Data/ResultTimeCell';
import ResultPositionCell from '../Data/ResultPositionCell';
import ResultSpanCell from '../Data/ResultSpanCell';
import ResultEntryCell from '../Data/ResultEntryCell';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

declare type SessionTableRowProps = {
    result: Result,
};

const SessionTableResult: React.FunctionComponent<SessionTableRowProps> = ({ result }) => (
    <StyledTableRow>
        <ResultPositionCell classifiedStatus={result.classified_status} position={result.finish_position} />
        <SessionTableCell>{result.car_number.toString()}</SessionTableCell>
        <ResultEntryCell countryCode={result.driver.country.code} name={result.driver.name} />
        <ResultEntryCell countryCode={result.team.country.code} name={result.team.name} />
        <SessionTableCell>{result.laps.toString()}</SessionTableCell>
        <ResultTimeCell time={result.race_time} />
        <ResultSpanCell classification={result.classified_status} time={result.gap_time} laps={result.gap_laps} />
        <ResultSpanCell
            classification={result.classified_status}
            time={result.interval_time}
            laps={result.interval_laps}
        />
        <ResultTimeCell time={result.best_lap_time} />
    </StyledTableRow>
);

export default SessionTableResult;
