'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

import formatSpan from '../../Utils/FormatSpan';
import { ResultClassification } from '../../Types/Index';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultSpanProps = {
    classification: ResultClassification,
    time: string,
    laps: number,
};

const ResultSpanCell: FunctionComponent<ResultSpanProps> = ({ classification, time, laps }) => (
    <StyledTableCell>
        <Typography noWrap>{formatSpan(classification, time, laps)}</Typography>
    </StyledTableCell>
);

export default ResultSpanCell;
