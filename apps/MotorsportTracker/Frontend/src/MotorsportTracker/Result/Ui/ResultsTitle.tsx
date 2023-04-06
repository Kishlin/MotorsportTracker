import React from 'react';
import Typography from '@mui/material/Typography';

import { SeasonEvent } from '../../Shared/Types';

declare type ResultsTitleProps = {
    event: SeasonEvent,
};

const ResultsTitle: React.FunctionComponent<ResultsTitleProps> = ({ event }) => (
    <Typography variant="h4" align="left" sx={{ my: 4 }}>{`${event.name} - Results`}</Typography>
);

export default ResultsTitle;
