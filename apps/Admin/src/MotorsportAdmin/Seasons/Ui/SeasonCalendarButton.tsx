import { faCalendar } from '@fortawesome/free-solid-svg-icons/faCalendar';
import { FunctionComponent, useState } from 'react';
import { Button } from '@mui/material';

import FontAwesomeSvgIcon from '../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import scrapCalendarApi from '../Api/ScrapCalendarApi';
import jobStatusApi from '../../Jobs/Api/JobsStatusApi';

declare type SeasonCalendarButtonProps = {
    onJobFinished: () => Promise<void>,
    seriesName: string;
    year: number,
};

const SeasonCalendarButton: FunctionComponent<SeasonCalendarButtonProps> = ({ seriesName, year, onJobFinished }) => {
    const [jobRunning, setJobRunning] = useState<null|string>(null);

    const checkSeasonsJobStatus = async (jobId: string) => {
        const job = await jobStatusApi(jobId);

        if ('finished' === job.status) {
            await onJobFinished();
            await setJobRunning(null);
        } else {
            setTimeout(
                () => {
                    checkSeasonsJobStatus(jobId);
                },
                500,
            );
        }
    };

    const startSeasonsJob = async () => {
        if (null === jobRunning || undefined === jobRunning) {
            setJobRunning('requested');

            const { uuid } = await scrapCalendarApi(seriesName, year);
            await setJobRunning(uuid);
            await checkSeasonsJobStatus(uuid);
        }
    };

    return (
        <Button onClick={startSeasonsJob} sx={{ p: 0, display: 'inline-block', height: 24 }}>
            <FontAwesomeSvgIcon color={jobRunning ? 'warning' : 'action'} icon={faCalendar} />
        </Button>
    );
};

export default SeasonCalendarButton;
