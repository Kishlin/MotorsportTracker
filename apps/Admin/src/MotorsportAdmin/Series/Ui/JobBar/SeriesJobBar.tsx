'use client';

import MuiAlert, { AlertProps } from '@mui/material/Alert';
import { Snackbar } from '@mui/material';
import Box from '@mui/material/Box';
import {
    forwardRef,
    FunctionComponent,
    SyntheticEvent,
    useEffect,
    useState,
} from 'react';

import SeriesJobBarContent from './SeriesJobBarContent';
import jobsApi from '../../../Jobs/Api/JobsApi';
import { Job } from '../../../Shared/Types';

const Alert = forwardRef<HTMLDivElement, AlertProps>((
    props,
    ref,
) => (
    <MuiAlert elevation={6} ref={ref} variant="filled" {...props} />
));

const SeriesJobBar: FunctionComponent = () => {
    const [message, setMessage] = useState<string>('');
    const [open, setOpen] = useState<boolean>(false);
    const [jobs, setJobs] = useState<Array<Job>>([]);

    const refreshJobs = async () => {
        setJobs(await jobsApi('scrap_series'));
    };

    const showSnackAlert = (newMessage: string) => {
        setMessage(newMessage);
        setOpen(true);

        refreshJobs();
    };

    const handleClose = (event?: SyntheticEvent | Event, reason?: string) => {
        if ('clickaway' === reason) {
            return;
        }

        setOpen(false);
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
            <SeriesJobBarContent jobs={jobs} onJobStarted={showSnackAlert} />
            <Snackbar open={open} autoHideDuration={6000} onClose={handleClose}>
                <Alert onClose={handleClose} severity="success" sx={{ width: '100%' }}>
                    {message}
                </Alert>
            </Snackbar>
        </Box>
    );
};

export default SeriesJobBar;
