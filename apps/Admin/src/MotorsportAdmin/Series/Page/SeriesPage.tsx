'use client';

import { SyntheticEvent, useEffect, useState } from 'react';

import SeriesTable from '../Ui/SeriesTable/SeriesTable';
import SeriesJobBar from '../Ui/JobBar/SeriesJobBar';
import seriesListApi from '../Api/SeriesListApi';
import { Job, Series } from '../../Shared/Types';
import jobsApi from '../../Jobs/Api/JobsApi';

const SeriesPage = () => {
    // const [series, setSeries] = useState<Array<Series>>([]);
    // const [message, setMessage] = useState<string>('');
    // const [open, setOpen] = useState<boolean>(false);
    // const [jobs, setJobs] = useState<Array<Job>>([]);
    //
    // const refreshSeries = async () => {
    //     setSeries(await seriesListApi());
    // };
    //
    // const refreshJobs = async () => {
    //     const newJobs = await jobsApi('scrap_series');
    //
    //     if (newJobs.length < jobs.length) {
    //         await refreshSeries();
    //     }
    //
    //     setJobs(newJobs);
    // };
    //
    // const showSnackAlert = (newMessage: string) => {
    //     setMessage(newMessage);
    //     setOpen(true);
    //
    //     refreshJobs();
    // };
    //
    // const handleClose = (event?: SyntheticEvent | Event, reason?: string) => {
    //     if ('clickaway' === reason) {
    //         return;
    //     }
    //
    //     setOpen(false);
    // };

    console.log('hello');

    useEffect(
        () => {
            // refreshJobs();
            // refreshSeries();

            console.log('hi');
            //
            // const interval = setInterval(
            //     () => {
            //         console.log('hey');
            //         // refreshJobs();
            //     },
            //     3000,
            // );
            //
            // return () => clearInterval(interval);
        },
        [],
    );

    return (
        <>
            <p>Hello world</p>
            {/*<SeriesJobBar*/}
            {/*    jobs={jobs}*/}
            {/*    open={open}*/}
            {/*    showSnackAlert={showSnackAlert}*/}
            {/*    handleClose={handleClose}*/}
            {/*    message={message}*/}
            {/*/>*/}
            {/*<SeriesTable*/}
            {/*    series={series}*/}
            {/*/>*/}
        </>
    );
};

export default SeriesPage;
