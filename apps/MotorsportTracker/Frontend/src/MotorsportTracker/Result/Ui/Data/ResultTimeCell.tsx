import Typography from '@mui/material/Typography';
import React from 'react';

import formatTime from '../../Utils/FormatTime';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultTimeProps = {
    time: null|string,
};

const ResultTimeCell: React.FunctionComponent<ResultTimeProps> = ({ time }) => (
    <StyledTableCell>
        <Typography noWrap>{null === time ? <noscript /> : formatTime(time)}</Typography>
    </StyledTableCell>
);

export default ResultTimeCell;
