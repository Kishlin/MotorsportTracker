export interface Series {
    color: string,
    index: number,
    car_number: number,
    short_code: string,
}

export interface GraphData<T extends Series> {
    session: {
        id: string,
        type: string,
    },
    series: Array<T>,
}

export interface LapByLapSeries extends Series {
    lapTimes: {
        [key: string]: number,
    },
}

export interface TyreHistorySeries extends Series {
    laps: number,
    tyre_history: {
        [key: string]: {
            laps: number,
            type: 'H'|'M'|'S'|'I'|'W',
            wear: 'n'|'u',
        }
    }
}

export interface PositionChangeSeries extends Series {
    changes: number,
    grid: number,
    finish: number,
}

export interface FastestLapSeries extends Series {
    fastest: string,
    delta: number,
}

export interface LapByLapGraphData extends GraphData<LapByLapSeries> {
    laps: number,
    lapTimes: {
        fastest: number,
        slowest: number,
    },
}

export interface TyreHistoryGraphData extends GraphData<TyreHistorySeries> {
    laps: number,
}

export interface PositionChangeGraphData extends GraphData<PositionChangeSeries> {
    minChanges: number,
    maxChanges: number,
}

export interface FastestLapGraphData extends GraphData<FastestLapSeries> {
    maxDelta: number,
}

export type EventGraphList<T> = {
    [key: string]: T,
};

export type EventGraphs = {
    'lap-by-lap-pace': EventGraphList<LapByLapGraphData>,
    'tyre-history': EventGraphList<TyreHistoryGraphData>,
    'position-change': EventGraphList<PositionChangeGraphData>,
    'fastest-lap': EventGraphList<FastestLapGraphData>,
};
