import Typography from '@mui/material/Typography';
import React from 'react';

import formatSpan from '../../Utils/FormatSpan';
import { ResultClassification } from '../../Types/Index';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultSpanProps = {
    classification: ResultClassification,
    time: string,
    laps: number,
};

const ResultSpanCell: React.FunctionComponent<ResultSpanProps> = ({ classification, time, laps }) => (
    <StyledTableCell>
        <Typography noWrap>{formatSpan(classification, time, laps)}</Typography>
    </StyledTableCell>
);

export default ResultSpanCell;
