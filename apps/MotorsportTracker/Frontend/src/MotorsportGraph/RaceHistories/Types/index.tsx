export type HistoriesSeries = {
    color: string,
    index: number,
    car_number: number,
    short_code: string,
    positions: {
        [key: string|number]: number,
    },
    pits: {
        [key: string|number]: number,
    }
};

export type HistoriesData = {
    session: {
        id: string,
        type: string,
    },
    laps: number,
    series: Array<HistoriesSeries>,
};

export type HistoriesList = {
    [key: string]: HistoriesData,
};
