import Typography from '@mui/material/Typography';
import React from 'react';

import { Result, ResultsBySession } from '../Types/Index';
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
    if (0 === Object.keys(results).length) {
        return <Typography align="center" sx={{ mt: 4 }}>No results are available at this time.</Typography>;
    }

    return (
        <>
            {Object.keys(results).map(
                (key: string) => (
                    <SessionContainer key={key}>
                        {withTitle ? <SessionTitle name={key} /> : <noscript />}
                        <SessionTable>
                            <SessionTableHead />
                            <SessionTableBody>
                                {results[key].map(
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
