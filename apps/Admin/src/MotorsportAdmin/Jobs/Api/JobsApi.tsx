import { Job, JobType } from '../../Shared/Types';

export type JobsApi = (type: JobType) => Promise<Array<Job>>;

const jobsApi: JobsApi = async (type: JobType) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/jobs/${type}`,
        {
            cache: 'no-store',
        },
    );

    return await response.json() as Array<Job>;
};

export default jobsApi;
