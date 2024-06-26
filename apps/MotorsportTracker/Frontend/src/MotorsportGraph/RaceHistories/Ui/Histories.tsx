'use client';

import { FunctionComponent } from 'react';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';

import HistoriesGraph from './HistoriesGraph';
import { HistoriesList } from '../Types';

declare type HistoriesProps = {
    histories: HistoriesList,
    isMultiDriver: boolean,
};

const Histories: FunctionComponent<HistoriesProps> = ({ histories, isMultiDriver }) => {
    if (0 === Object.keys(histories).length) {
        return <Typography align="center">There are no Histories available at this time.</Typography>;
    }

    const graphs = Object.keys(histories).map((key: string) => (
        <Box key={key}>
            <HistoriesGraph data={histories[key]} isMultiDriver={isMultiDriver} />
        </Box>
    ));

    return (
        <Box sx={{ mb: 2 }}>
            {graphs}
        </Box>
    );
};

export default Histories;
