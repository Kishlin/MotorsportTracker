'use client';

import Box from '@mui/material/Box';
import {
    FunctionComponent,
    useContext,
    useEffect,
    useState,
} from 'react';

import { SnackbarContext, SnackbarContextType } from '../../../../Shared/Context/Snackbar/SnackbarContext';
import { SeriesContext, SeriesContextType } from '../../Context/SeriesContext';
import jobStatusApi from '../../../Jobs/Api/JobsStatusApi';
import SeriesJobBarContent from './SeriesJobBarContent';
import scrapSeriesApi from '../../Api/ScrapSeriesApi';
import jobsApi from '../../../Jobs/Api/JobsApi';
import { Job } from '../../../Shared/Types';

const SeriesJobBar: FunctionComponent = () => {
    const [jobs, setJobs] = useState<Array<Job>>([]);

    const { showSnackAlert } = useContext<SnackbarContextType>(SnackbarContext);
    const { refreshSeries } = useContext<SeriesContextType>(SeriesContext);

    const refreshJobs = async () => {
        const newJobs = await jobsApi('scrap_series');

        await setJobs(newJobs);
    };

    const checkJobStatus = async (id: string) => {
        const job = await jobStatusApi(id);

        if ('finished' === job.status) {
            await refreshSeries();
            await refreshJobs();
        } else {
            setTimeout(
                () => {
                    checkJobStatus(id);
                },
                500,
            );
        }
    };

    const startANewJob = async () => {
        const response = await scrapSeriesApi();

        const tempJob = {
            started_on: new Date().toISOString(),
            type: 'scrap_series',
            status: 'requested',
            finished_on: null,
            id: response.uuid,
            params: '{}',
        } as Job;

        await setJobs([tempJob, ...jobs]);

        showSnackAlert(`Job started: ${response.uuid}`);
        await refreshJobs();
        await checkJobStatus(response.uuid);
    };

    useEffect(
        () => {
            refreshJobs();

            const interval = setInterval(
                () => {
                    refreshJobs();
                },
                10000,
            );

            return () => clearInterval(interval);
        },
        [],
    );

    return (
        <Box sx={{ my: 2 }}>
            <SeriesJobBarContent jobs={jobs} onJobRequested={startANewJob} />
        </Box>
    );
};

export default SeriesJobBar;
