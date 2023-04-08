import Typography from '@mui/material/Typography';
import React from 'react';

declare type LapByLapTitleProps = {
    type: string,
};

const LapByLapTitle: React.FunctionComponent<LapByLapTitleProps> = ({ type }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>
        {`${type} - Lap by Lap Pace`}
    </Typography>
);

export default LapByLapTitle;
