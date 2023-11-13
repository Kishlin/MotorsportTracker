import { faRankingStar } from '@fortawesome/free-solid-svg-icons/faRankingStar';
import { FunctionComponent, useState } from 'react';
import { Button } from '@mui/material';

import FontAwesomeSvgIcon from '../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import scrapStandingsApi from '../Api/ScrapStandingsApi';
import jobStatusApi from '../../Jobs/Api/JobsStatusApi';

declare type SeasonStandingsButtonProps = {
    onJobFinished: () => Promise<void>,
    seriesName: string;
    year: number,
};

const SeasonStandingsButton: FunctionComponent<SeasonStandingsButtonProps> = ({ seriesName, year, onJobFinished }) => {
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

            const { uuid } = await scrapStandingsApi(seriesName, year);
            await setJobRunning(uuid);
            await checkSeasonsJobStatus(uuid);
        }
    };

    return (
        <Button onClick={startSeasonsJob} sx={{ p: 0, display: 'inline-block', height: 24 }}>
            <FontAwesomeSvgIcon color={jobRunning ? 'warning' : 'action'} icon={faRankingStar} />
        </Button>
    );
};

export default SeasonStandingsButton;
