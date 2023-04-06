import Typography from '@mui/material/Typography';
import React from 'react';

import SessionTableCell from '../Table/SessionTableCell';
import formatSpan from '../../Utils/FormatSpan';
import { ResultClassification } from '../../Types/Index';

declare type ResultSpanProps = {
    classification: ResultClassification,
    time: string,
    laps: number,
};

const ResultSpanCell: React.FunctionComponent<ResultSpanProps> = ({ classification, time, laps }) => (
    <SessionTableCell>
        <Typography noWrap>{formatSpan(classification, time, laps)}</Typography>
    </SessionTableCell>
);

export default ResultSpanCell;
