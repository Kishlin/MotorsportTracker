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
    sessions: Array<{
        type: string,
        slug: string,
        has_result: false,
        start_date: null|string,
        end_date: null|string,
    }>
};

export type EventsSchedule = {
    [key: `${string}-${string}-${string}`]: MotorsportEvent[]
};
