import Typography from '@mui/material/Typography';
import React from 'react';

import { SessionResult, Result, ResultsBySession } from '../Types/Index';
import SessionTableResult from './Table/SessionTableResult';
import SessionContainer from './Table/SessionContainer';
import SessionTableHead from './Table/SessionTableHead';
import SessionTableBody from './Table/SessionTableBody';
import SessionTable from './Table/SessionTable';
import SessionTitle from './Table/SessionTitle';

declare type ResultsContentProps = {
    results: ResultsBySession,
    withTitle: boolean,
};

const ResultsContent: React.FunctionComponent<ResultsContentProps> = ({ results, withTitle }) => {
    if (0 === results.length) {
        return <Typography align="center" sx={{ mt: 4 }}>No results are available at this time.</Typography>;
    }

    return (
        <>
            {results.map(
                (raceResult: SessionResult) => (
                    <SessionContainer key={raceResult.session.id}>
                        {withTitle ? <SessionTitle name={raceResult.session.type} /> : <noscript />}
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
