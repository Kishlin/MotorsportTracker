import { Country } from '../../../Shared/Types/Index';

export type ResultTeamWithCountry = {
    id: string,
    short_code: string,
    name: string,
    country: null|Country,
};

export interface ResultDriver {
    id: string,
    short_code: string,
    name: string,
};

export interface ResultDriverWithCountry extends ResultDriver {
    country: null|Country,
}

export type ResultClassification = null | 'CLA' | 'DNF' | 'DNS' | 'WIT';

export type Result = {
    driver: ResultDriverWithCountry,
    additional_drivers: ResultDriver[],
    team: ResultTeamWithCountry,
    car_number: number,
    finish_position: number,
    classified_status: ResultClassification,
    grid_position: null|number,
    laps: number,
    race_time: string,
    average_lap_speed: string,
    best_lap_time: null|string,
    best_lap: null|number,
    best_is_fastest: null|boolean,
    best_speed: null|number,
    gap_time: string,
    interval_time: string,
    gap_laps: number,
    interval_laps: number,
};

export type ResultsBySession = {
    [key: string]: Result[],
};
