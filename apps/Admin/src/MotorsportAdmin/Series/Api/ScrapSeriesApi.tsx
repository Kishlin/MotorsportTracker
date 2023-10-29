import { StartJobApi } from '../../Shared/Types';

const jobsApi: StartJobApi = async () => {
    const response = await fetch(
        `${process.env.NEXT_PUBLIC_BACKOFFICE_URL}/api/v1/series/`,
        {
            method: 'POST',
            cache: 'no-store',
        },
    );

    return await response.json() as { uuid: string };
};

export default jobsApi;
