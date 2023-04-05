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
    start_date: string,
    end_date: string,
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
    [key: `${string}-${string}-${string}`]: MotorsportEvent[]
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
};

export type ChampionshipList = {
    [key: string]: Championship,
};
