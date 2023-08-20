'use client';

import TableRow from '@mui/material/TableRow';
import { styled } from '@mui/material/styles';
import { FunctionComponent } from 'react';

import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';
import ResultPositionCell from '../Data/ResultPositionCell';
import ResultEntryCell from '../Data/ResultEntryCell';
import ResultTimeCell from '../Data/ResultTimeCell';
import ResultSpanCell from '../Data/ResultSpanCell';
import { Result } from '../../Types/Index';
import ResultDriversCell from '../Data/ResultDriversCell';

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
    withComparison: boolean,
    result: Result,
};

const SessionTableResult: FunctionComponent<SessionTableRowProps> = ({ result, withComparison }) => (
    <StyledTableRow>
        <ResultPositionCell classifiedStatus={result.classified_status} position={result.finish_position} />
        <StyledTableCell>{result.car_number.toString()}</StyledTableCell>
        <ResultDriversCell driver={result.driver} additionalDrivers={result.additional_drivers} />
        <ResultEntryCell country={result.team.country} name={result.team.name} />
        <StyledTableCell>{result.laps.toString()}</StyledTableCell>
        <ResultTimeCell time={result.race_time} />
        {withComparison && (
            <>
                <ResultSpanCell
                    classification={result.classified_status}
                    time={result.gap_time}
                    laps={result.gap_laps}
                />
                <ResultSpanCell
                    classification={result.classified_status}
                    time={result.interval_time}
                    laps={result.interval_laps}
                />
                <ResultTimeCell time={result.best_lap_time} />
            </>
        )}
    </StyledTableRow>
);

export default SessionTableResult;
