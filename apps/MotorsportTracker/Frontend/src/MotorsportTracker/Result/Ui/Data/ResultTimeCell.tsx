'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

import formatTime from '../../Utils/FormatTime';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultTimeProps = {
    time: null|string,
};

const ResultTimeCell: FunctionComponent<ResultTimeProps> = ({ time }) => (
    <StyledTableCell>
        <Typography noWrap>{null === time ? <noscript /> : formatTime(time)}</Typography>
    </StyledTableCell>
);

export default ResultTimeCell;
