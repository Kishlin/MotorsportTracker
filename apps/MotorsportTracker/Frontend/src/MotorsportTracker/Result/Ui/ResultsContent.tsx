import Typography from '@mui/material/Typography';
import MenuItem from '@mui/material/MenuItem';
import React, { useEffect } from 'react';

import SESSION_TYPES_WITH_COMPARISON from '../../Config/SessionTypesWithComparison';
import ORDERED_SESSION_TYPES from '../../Config/OrderedSessionTypes';
import SessionTableResult from './Table/SessionTableResult';
import { Result, ResultsBySession } from '../Types/Index';
import SessionContainer from './Table/SessionContainer';
import SessionTableHead from './Table/SessionTableHead';
import SessionTableBody from './Table/SessionTableBody';
import TypeSelector from './TypeSelector/TypeSelector';
import SessionTable from './Table/SessionTable';

declare type ResultsContentProps = {
    results: ResultsBySession,
};

const ResultsContent: React.FunctionComponent<ResultsContentProps> = ({ results }) => {
    if (0 === Object.keys(results).length) {
        return <Typography align="center" sx={{ mt: 4 }}>No results are available at this time.</Typography>;
    }

    const applicableCategories = ORDERED_SESSION_TYPES.filter((type: string) => undefined !== results[type]);

    const [selectedType, setSelectedType] = React.useState<string>(applicableCategories[0]);

    useEffect(
        () => {
            setSelectedType(applicableCategories[0]);
        },
        [results],
    );

    const handleTypeSelectorChange = (targetType: string) => {
        setSelectedType(targetType);
    };

    const typeSelectorItems = applicableCategories.map((type: string) => (
        <MenuItem key={type} value={type}>
            {type}
        </MenuItem>
    ));

    let typeSelector = (<noscript />);
    if (1 < applicableCategories.length) {
        typeSelector = (
            <TypeSelector onChange={handleTypeSelectorChange} selected={selectedType}>
                {typeSelectorItems}
            </TypeSelector>
        );
    }

    const withComparison = SESSION_TYPES_WITH_COMPARISON.includes(selectedType);

    const resultRows = results[selectedType].map(
        (result: Result) => (
            <SessionTableResult withComparison={withComparison} key={result.car_number} result={result} />
        ),
    );

    return (
        <>
            {typeSelector}
            <SessionContainer>
                <SessionTable>
                    <SessionTableHead withComparison={withComparison} />
                    <SessionTableBody>
                        {resultRows}
                    </SessionTableBody>
                </SessionTable>
            </SessionContainer>
        </>
    );
};

export default ResultsContent;
