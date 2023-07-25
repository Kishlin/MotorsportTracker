import TableRow from '@mui/material/TableRow';
import { styled } from '@mui/material/styles';
import React from 'react';

import { Result } from '../../Types/Index';
import ResultTimeCell from '../Data/ResultTimeCell';
import ResultPositionCell from '../Data/ResultPositionCell';
import ResultSpanCell from '../Data/ResultSpanCell';
import ResultEntryCell from '../Data/ResultEntryCell';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

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
        <StyledTableCell>{result.car_number.toString()}</StyledTableCell>
        <ResultEntryCell countryCode={result.driver.country.code} name={result.driver.name} />
        <ResultEntryCell countryCode={result.team.country.code} name={result.team.name} />
        <StyledTableCell>{result.laps.toString()}</StyledTableCell>
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
