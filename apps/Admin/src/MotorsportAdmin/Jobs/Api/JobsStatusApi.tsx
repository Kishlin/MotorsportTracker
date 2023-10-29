import { Job } from '../../Shared/Types';

export type JobsApi = (id: string) => Promise<Job>;

const jobStatusApi: JobsApi = async (id: string) => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/jobs/${id}`,
        {
            cache: 'no-store',
        },
    );

    return await response.json() as Job;
};

export default jobStatusApi;
