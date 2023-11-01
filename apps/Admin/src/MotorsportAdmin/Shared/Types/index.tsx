export type JobType = 'scrap_series'|'scrap_seasons';

export type JobStatus = 'requested'|'running'|'finished';

export type Job = {
    id: string;
    type: JobType;
    status: JobStatus;
    params: string;
    started_on: string;
    finished_on: null|string;
}

export type StartJobApi = () => Promise<{ uuid: string }>;

export type Series = {
    id: string;
    name: string;
    short_name: string;
    short_code: string;
    ref: string;
    seasons: number;
};

export type Season = {
    id: string,
    series: string,
    year: number,
    ref: string,
    standings: number,
    events: number,
};
