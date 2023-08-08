import React from 'react';
import Typography from '@mui/material/Typography';

declare type RaceResultTitleProps = {
    name: string,
};

const SessionTitle: React.FunctionComponent<RaceResultTitleProps> = ({ name }) => (
    <Typography variant="h5" align="left" sx={{ my: 4 }}>{name}</Typography>
);

export default SessionTitle;
