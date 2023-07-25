import Typography from '@mui/material/Typography';
import React from 'react';

import formatTime from '../../Utils/FormatTime';
import StyledTableCell from '../../../../Shared/Ui/Table/StyledTableCell';

declare type ResultTimeProps = {
    time: string,
};

const ResultTimeCell: React.FunctionComponent<ResultTimeProps> = ({ time }) => (
    <StyledTableCell>
        <Typography noWrap>{formatTime(time)}</Typography>
    </StyledTableCell>
);

export default ResultTimeCell;
