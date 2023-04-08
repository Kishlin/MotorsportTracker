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
    series: Array<{
        color: string,
        label: string,
        dashed: boolean,
        lapTimes: number[],
    }>,
};

export type EventGraphList<T> = {
    [key: string]: T,
};

export type EventGraphs = {
    'lap-by-lap-pace': EventGraphList<LapByLapGraphData>,
};
