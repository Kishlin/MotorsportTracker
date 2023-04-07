import React from 'react';
import Typography from '@mui/material/Typography';

declare type GraphTitleProps = {
    title: string,
};

const GraphTitle: React.FunctionComponent<GraphTitleProps> = ({ title }) => (
    <Typography variant="h4" align="left" sx={{ mb: 1 }}>{title}</Typography>
);

export default GraphTitle;
