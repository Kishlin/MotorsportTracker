import { Country } from '../../../Shared/Types/Index';

export type ResultTeam = {
    id: string,
    short_code: string,
    name: string,
    country: Country,
};

export type ResultDriver = {
    id: string,
    short_code: string,
    name: string,
    country: Country,
};

export type ResultClassification = 'CLA' | 'DNF' | 'DNS' | 'WIT';

export type Result = {
    driver: ResultDriver,
    team: ResultTeam,
    car_number: number,
    finish_position: number,
    classified_status: ResultClassification,
    grid_position: number,
    laps: number,
    race_time: string,
    average_lap_speed: string,
    best_lap_time: string,
    best_lap: number,
    best_is_fastest: boolean,
    gap_time: string,
    interval_time: string,
    gap_laps: number,
    interval_laps: number,
};

export type RaceResult = {
    session: {
        id: string,
        type: string,
    },
    result: Result[],
};

export type ResultsByRace = {
    event: string,
    resultsByRace: Array<RaceResult>,
};
