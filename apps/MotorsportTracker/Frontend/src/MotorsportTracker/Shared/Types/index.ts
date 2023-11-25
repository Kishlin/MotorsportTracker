import { Country } from '../../../Shared/Types/Index';

export type MotorsportSession = {
    type: string,
    slug: string,
    has_result: false,
    start_date: null|string,
    end_date: null|string,
};

export type MotorsportEvent = {
    id: string,
    slug: string,
    index: number,
    name: string,
    short_name: string,
    status: null|string,
    start_date: null|string,
    end_date: null|string,
    series: {
        name: string,
        slug: string,
        year: number,
        icon: string,
        color: string,
    },
    venue: {
        name: string,
        slug: string,
        country: {
            code: string,
            name: string,
        },
    },
    sessions: Array<MotorsportSession>,
};

export type SeasonEvent = {
    id: string,
    slug: string,
    name: string,
    index: number,
};

export type EventShort = {
    championship: string,
    year: number,
    event: string,
};

export type EventsSchedule = {
    [key: `${string}-${string}-${string}`]: MotorsportEvent[],
    'no-date' ?: MotorsportEvent[],
};

export type SeasonEvents = {
    [key: string]: SeasonEvent,
};
export type EventsList = EventShort[];

export type Championship = {
    name: string,
    displayName: string,
    shortName: string,
    slug: string,
    years: number[],
    isMultiDriver: boolean,
    menuIconFormat: null|'svg'|'png',
};

export type ChampionshipList = {
    [key: string]: Championship,
};

export interface Standing {
    id: string,
    name: string,
    position: number,
    points: number,
    color: null|string,
    country: null|Country,
}

export interface ConstructorStanding extends Standing {}

export interface TeamStanding extends Standing {
    color: string,
}

export interface DriverStanding extends Standing {
    short_code: null|string,
    color: null|string,
}

export type AvailableStandings = {
    constructor: boolean,
    team: boolean,
    driver: boolean,
};

export type StandingType = 'constructor'|'team'|'driver';
