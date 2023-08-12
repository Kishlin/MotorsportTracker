import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import React from 'react';

import LapByLapGraph from '../../LapByLapRacePace/Ui/LapByLapGraph';
import { EventGraphs } from '../Types';

declare type GraphsProps = {
    graphs: EventGraphs,
    isMultiDriver: boolean,
};

const Graphs: React.FunctionComponent<GraphsProps> = ({ graphs, isMultiDriver }) => {
    if (0 === Object.keys(graphs).length) {
        return <Typography align="center">There are no graphs available at this time.</Typography>;
    }

    const graphsJSX = Object.keys(graphs['lap-by-lap-pace']).map((key: string) => (
        <LapByLapGraph key={key} isMultiDriver={isMultiDriver} data={graphs['lap-by-lap-pace'][key]} />
    ));

    return (
        <Box>
            {graphsJSX}
        </Box>
    );
};

export default Graphs;
