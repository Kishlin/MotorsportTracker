import Typography from '@mui/material/Typography';
import React from 'react';

import SessionTableCell from '../Table/SessionTableCell';
import formatTime from '../../Utils/FormatTime';

declare type ResultTimeProps = {
    time: string,
};

const ResultTimeCell: React.FunctionComponent<ResultTimeProps> = ({ time }) => (
    <SessionTableCell>
        <Typography noWrap>{formatTime(time)}</Typography>
    </SessionTableCell>
);

export default ResultTimeCell;
