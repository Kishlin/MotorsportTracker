import Typography from '@mui/material/Typography';
import React from 'react';

import { RaceResult, Result, ResultsByRace } from '../Types/Index';
import SessionTableResult from './Table/SessionTableResult';
import SessionContainer from './Table/SessionContainer';
import SessionTableHead from './Table/SessionTableHead';
import SessionTableBody from './Table/SessionTableBody';
import { SeasonEvent } from '../../Shared/Types';
import SessionTable from './Table/SessionTable';
import SessionTitle from './Table/SessionTitle';

declare type ResultsContentProps = {
    results: ResultsByRace,
    withTitle: boolean,
    event: SeasonEvent,
};

const ResultsContent: React.FunctionComponent<ResultsContentProps> = ({ results, withTitle, event }) => {
    if (0 === results.resultsByRace.length) {
        return <Typography align="center" sx={{ mt: 4 }}>No results are available at this time.</Typography>;
    }

    return (
        <>
            {results.resultsByRace.map(
                (raceResult: RaceResult) => (
                    <SessionContainer key={raceResult.session.id}>
                        {withTitle ? <SessionTitle name={event.name} /> : <noscript />}
                        <SessionTable>
                            <SessionTableHead />
                            <SessionTableBody>
                                {raceResult.result.map(
                                    (result: Result) => (
                                        <SessionTableResult key={result.car_number} result={result} />
                                    ),
                                )}
                            </SessionTableBody>
                        </SessionTable>
                    </SessionContainer>
                ),
            )}
        </>
    );
};

export default ResultsContent;
