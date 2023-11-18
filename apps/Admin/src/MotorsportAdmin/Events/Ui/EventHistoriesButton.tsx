import { faChartLine } from '@fortawesome/free-solid-svg-icons/faChartLine';
import { FunctionComponent, useState } from 'react';
import { Button } from '@mui/material';

import FontAwesomeSvgIcon from '../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import scrapHistoriesApi from '../Api/ScrapHistoriesApi';
import jobStatusApi from '../../Jobs/Api/JobsStatusApi';

declare type EventHistoriesButtonProps = {
    onJobFinished: () => Promise<void>,
    seriesName: string;
    year: number,
    event: string,
};

const EventHistoriesButton: FunctionComponent<EventHistoriesButtonProps> = ({
    seriesName,
    year,
    event,
    onJobFinished,
}) => {
    const [jobRunning, setJobRunning] = useState<null|string>(null);

    const checkHistoriesJobStatus = async (jobId: string) => {
        const job = await jobStatusApi(jobId);

        if ('finished' === job.status) {
            await onJobFinished();
            await setJobRunning(null);
        } else {
            setTimeout(
                () => {
                    checkHistoriesJobStatus(jobId);
                },
                500,
            );
        }
    };

    const startSeasonsJob = async () => {
        if (null === jobRunning || undefined === jobRunning) {
            setJobRunning('requested');

            const { uuid } = await scrapHistoriesApi(seriesName, year, event);
            await setJobRunning(uuid);
            await checkHistoriesJobStatus(uuid);
        }
    };

    return (
        <Button onClick={startSeasonsJob} sx={{ p: 0, display: 'inline-block', height: 24 }}>
            <FontAwesomeSvgIcon color={jobRunning ? 'warning' : 'action'} icon={faChartLine} />
        </Button>
    );
};

export default EventHistoriesButton;
