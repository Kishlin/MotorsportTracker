import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import React from 'react';

import PositionChangeGraph from '../../PositionChange/Ui/PositionChangeGraph';
import TyreHistoryGraph from '../../TyreHistory/Ui/TyreHistoryGraph';
import LapByLapGraph from '../../LapByLapRacePace/Ui/LapByLapGraph';
import FastestLapGraph from '../../FastestLap/Ui/FastestLapGraph';
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
            <TyreHistoryGraph key={key} data={graphs['tyre-history'][key]} isMultiDriver={isMultiDriver} />
        ));
    }

    let positionChangesGraphsJSX: React.ReactNode[] = [];
    if (undefined !== graphs['position-change']) {
        positionChangesGraphsJSX = Object.keys(graphs['position-change']).map((key: string) => (
            <PositionChangeGraph key={key} data={graphs['position-change'][key]} isMultiDriver={isMultiDriver} />
        ));
    }

    let fastestLapGraphsJSX: React.ReactNode[] = [];
    if (undefined !== graphs['fastest-lap']) {
        fastestLapGraphsJSX = Object.keys(graphs['fastest-lap']).map((key: string) => (
            <FastestLapGraph key={key} data={graphs['fastest-lap'][key]} isMultiDriver={isMultiDriver} />
        ));
    }

    return (
        <Box>
            {lapByLapGraphsJSX}
            {tyreHistoryGraphsJSX}
            {positionChangesGraphsJSX}
            {fastestLapGraphsJSX}
        </Box>
    );
};

export default Graphs;
