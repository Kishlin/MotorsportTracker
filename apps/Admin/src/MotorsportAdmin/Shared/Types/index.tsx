export type JobType = 'scrap_series'|'scrap_seasons';

export type JobStatus = 'requested'|'running'|'success';

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
};
