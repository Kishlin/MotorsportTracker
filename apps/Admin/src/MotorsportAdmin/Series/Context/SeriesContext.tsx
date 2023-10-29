'use client';

import {
    FunctionComponent,
    createContext,
    ReactNode,
    useState,
    useMemo,
} from 'react';

import { Series } from '../../Shared/Types';
import seriesListApi from '../Api/SeriesListApi';

export type SeriesContextType = {
    refreshSeries: () => Promise<void>,
    series: Array<Series>,
    current: null|Series,
};

const defaultValue: SeriesContextType = {
    refreshSeries: async () => new Promise(() => {}),
    current: null,
    series: [],
};

export const SeriesContext = createContext<SeriesContextType>(defaultValue);

export const SeriesProvider: FunctionComponent<{ children: ReactNode}> = ({ children }) => {
    const [series, setSeries] = useState<Array<Series>>([]);

    const refreshSeries = async () => {
        setSeries(await seriesListApi());
    };

    const context = useMemo<SeriesContextType>(
        () => ({
            refreshSeries,
            current: null,
            series,
        }),
        [series],
    );

    return (
        <SeriesContext.Provider value={context}>
            {children}
        </SeriesContext.Provider>
    );
};
