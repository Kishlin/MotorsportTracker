'use client';

import Typography from '@mui/material/Typography';
import { FunctionComponent } from 'react';

declare type HistoriesTitleProps = {
    type: string,
};

const HistoriesTitle: FunctionComponent<HistoriesTitleProps> = ({ type }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>
        {type}
    </Typography>
);

export default HistoriesTitle;
