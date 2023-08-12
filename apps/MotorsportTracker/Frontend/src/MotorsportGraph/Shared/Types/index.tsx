export type LapByLapSeries = {
    color: string,
    index: number,
    car_number: number,
    short_code: string,
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
