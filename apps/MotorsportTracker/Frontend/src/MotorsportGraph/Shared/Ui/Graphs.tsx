import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import React from 'react';

import TyreHistoryGraph from '../../TyreHistory/Ui/TyreHistoryGraph';
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

    let lapByLapGraphsJSX: React.ReactElement[] = [];
    if (undefined !== graphs['lap-by-lap-pace']) {
        lapByLapGraphsJSX = Object.keys(graphs['lap-by-lap-pace']).map((key: string) => (
            <LapByLapGraph key={key} isMultiDriver={isMultiDriver} data={graphs['lap-by-lap-pace'][key]} />
        ));
    }

    let tyreHistoryGraphsJSX: React.ReactElement[] = [];
    if (undefined !== graphs['tyre-history']) {
        tyreHistoryGraphsJSX = Object.keys(graphs['tyre-history']).map((key: string) => (
            <TyreHistoryGraph key={key} data={graphs['tyre-history'][key]} />
        ));
    }

    return (
        <Box>
            {lapByLapGraphsJSX}
            {tyreHistoryGraphsJSX}
        </Box>
    );
};

export default Graphs;
