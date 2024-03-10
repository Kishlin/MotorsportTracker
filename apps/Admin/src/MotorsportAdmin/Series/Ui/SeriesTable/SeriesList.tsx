'use client';

import {
    useState,
    useEffect,
    useContext,
    ChangeEvent,
    FunctionComponent,
} from 'react';
import { FormControlLabel, FormGroup, Checkbox } from '@mui/material';

import { SeriesContext, SeriesContextType } from '../../Context/SeriesContext';
import SeriesTable from './SeriesTable';

const SeriesList: FunctionComponent = () => {
    const [showEmptySeries, setShowEmptySeries] = useState<boolean>(false);
    const { series, refreshSeries } = useContext<SeriesContextType>(SeriesContext);

    const handleChecked = (event: ChangeEvent<HTMLInputElement>) => {
        setShowEmptySeries(event.target.checked);
    };

    useEffect(
        () => {
            refreshSeries();
        },
        [],
    );

    const filteredSeries = showEmptySeries ? series : series.filter((item) => 0 < item.seasons);

    return (
        <>
            <SeriesTable series={filteredSeries} />
            <FormGroup>
                <FormControlLabel
                    control={(
                        <Checkbox
                            onChange={handleChecked}
                            checked={showEmptySeries}
                            inputProps={{ 'aria-label': 'Show Empty Series' }}
                        />
                    )}
                    label="Show Empty Series"
                />
            </FormGroup>
        </>
    );
};

export default SeriesList;
