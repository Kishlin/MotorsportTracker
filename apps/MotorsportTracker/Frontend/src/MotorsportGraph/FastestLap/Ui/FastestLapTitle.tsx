import Typography from '@mui/material/Typography';
import React from 'react';

declare type FastestLapTitleProps = {
    type: string,
};

const FastestLapTitle: React.FunctionComponent<FastestLapTitleProps> = ({ type }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>
        {`${type} - Fastest Lap`}
    </Typography>
);

export default FastestLapTitle;
