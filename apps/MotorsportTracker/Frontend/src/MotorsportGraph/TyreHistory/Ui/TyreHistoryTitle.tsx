import Typography from '@mui/material/Typography';
import React from 'react';

declare type TyreHistoryTitleProps = {
    type: string,
};

const TyreHistoryTitle: React.FunctionComponent<TyreHistoryTitleProps> = ({ type }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>
        {`${type} - Tyre Strategy`}
    </Typography>
);

export default TyreHistoryTitle;
