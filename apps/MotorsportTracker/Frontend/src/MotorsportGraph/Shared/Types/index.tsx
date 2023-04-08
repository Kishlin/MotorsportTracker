export type LapByLapSeries = {
    color: string,
    label: string,
    dashed: boolean,
    lapTimes: {
        [key: string]: number,
    },
};

export type LapByLapGraphData = {
    session: {
        id: string,
        type: string,
    },
    laps: number,
    lapTimes: {
        fastest: number,
        slowest: number,
    },
    series: Array<LapByLapSeries>,
};

export type EventGraphList<T> = {
    [key: string]: T,
};

export type EventGraphs = {
    'lap-by-lap-pace': EventGraphList<LapByLapGraphData>,
};
