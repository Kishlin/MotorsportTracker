import Typography from '@mui/material/Typography';
import React from 'react';

declare type HistoriesTitleProps = {
    type: string,
};

const HistoriesTitle: React.FunctionComponent<HistoriesTitleProps> = ({ type }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>
        {type}
    </Typography>
);

export default HistoriesTitle;
