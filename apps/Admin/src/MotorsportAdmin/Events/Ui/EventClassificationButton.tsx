import { faRoute } from '@fortawesome/free-solid-svg-icons/faRoute';
import { FunctionComponent, useState } from 'react';
import { Button } from '@mui/material';

import FontAwesomeSvgIcon from '../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import scrapClassificationsApi from '../Api/ScrapClassificationsApi';
import jobStatusApi from '../../Jobs/Api/JobsStatusApi';

declare type EventClassificationButtonProps = {
    onJobFinished: () => Promise<void>,
    seriesName: string;
    year: number,
    event: string,
};

const EventClassificationButton: FunctionComponent<EventClassificationButtonProps> = ({
    seriesName,
    year,
    event,
    onJobFinished,
}) => {
    const [jobRunning, setJobRunning] = useState<null|string>(null);

    const checkClassificationsJobStatus = async (jobId: string) => {
        const job = await jobStatusApi(jobId);

        if ('finished' === job.status) {
            await onJobFinished();
            await setJobRunning(null);
        } else {
            setTimeout(
                () => {
                    checkClassificationsJobStatus(jobId);
                },
                500,
            );
        }
    };

    const startSeasonsJob = async () => {
        if (null === jobRunning || undefined === jobRunning) {
            setJobRunning('requested');

            const { uuid } = await scrapClassificationsApi(seriesName, year, event);
            await setJobRunning(uuid);
            await checkClassificationsJobStatus(uuid);
        }
    };

    return (
        <Button onClick={startSeasonsJob} sx={{ p: 0, display: 'inline-block', height: 24 }}>
            <FontAwesomeSvgIcon color={jobRunning ? 'warning' : 'action'} icon={faRoute} />
        </Button>
    );
};

export default EventClassificationButton;
