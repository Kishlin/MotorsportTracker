'use client';

import Box from '@mui/material/Box';
import {
    FunctionComponent,
    useContext,
    useEffect,
    useState,
} from 'react';

import { SnackbarContext, SnackbarContextType } from '../../../../Shared/Context/Snackbar/SnackbarContext';
import SeriesJobBarContent from './SeriesJobBarContent';
import jobsApi from '../../../Jobs/Api/JobsApi';
import { Job } from '../../../Shared/Types';

const SeriesJobBar: FunctionComponent = () => {
    const [jobs, setJobs] = useState<Array<Job>>([]);

    const { showSnackAlert } = useContext<SnackbarContextType>(SnackbarContext);

    const refreshJobs = async () => {
        setJobs(await jobsApi('scrap_series'));
    };

    const showAlertAndRefreshJobs = async (id: string) => {
        showSnackAlert(`Job started: ${id}`);
        const tempJob = {
            started_on: new Date().toISOString(),
            type: 'scrap_series',
            status: 'requested',
            finished_on: null,
            params: '{}',
            id,
        } as Job;

        setJobs([...jobs, tempJob]);
    };

    useEffect(
        () => {
            refreshJobs();

            const interval = setInterval(
                () => {
                    refreshJobs();
                },
                3000,
            );

            return () => clearInterval(interval);
        },
        [],
    );

    return (
        <Box sx={{ my: 2 }}>
            <SeriesJobBarContent jobs={jobs} onJobStarted={showAlertAndRefreshJobs} />
        </Box>
    );
};

export default SeriesJobBar;
