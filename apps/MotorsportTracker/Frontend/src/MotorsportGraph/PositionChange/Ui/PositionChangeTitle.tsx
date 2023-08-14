import Typography from '@mui/material/Typography';
import React from 'react';

declare type PositionChangeTitleProps = {
    type: string,
};

const PositionChangeTitle: React.FunctionComponent<PositionChangeTitleProps> = ({ type }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>
        {`${type} - Position Changes`}
    </Typography>
);

export default PositionChangeTitle;
