import { faRotate } from '@fortawesome/free-solid-svg-icons/faRotate';
import { FunctionComponent, useContext, useState } from 'react';
import { Button } from '@mui/material';

import FontAwesomeSvgIcon from '../../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import { SeriesContext, SeriesContextType } from '../../Context/SeriesContext';
import scrapSeasonsApi from '../../../Seasons/Api/ScrapSeasonsApi';
import jobStatusApi from '../../../Jobs/Api/JobsStatusApi';
import { Series } from '../../../Shared/Types';

declare type SeriesSeasonsButtonProps = {
    series: Series;
};

const SeriesSeasonsButton: FunctionComponent<SeriesSeasonsButtonProps> = ({ series }) => {
    const [jobRunning, setJobRunning] = useState<null|string>(null);

    const { refreshSeries } = useContext<SeriesContextType>(SeriesContext);

    const checkSeasonsJobStatus = async (jobId: string) => {
        const job = await jobStatusApi(jobId);

        if ('finished' === job.status) {
            await refreshSeries();
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

            const { uuid } = await scrapSeasonsApi(series.name);
            await setJobRunning(uuid);
            await checkSeasonsJobStatus(uuid);
        }
    };

    return (
        <Button onClick={startSeasonsJob} sx={{ p: 0, display: 'inline-block', height: 24 }}>
            <FontAwesomeSvgIcon color={jobRunning ? 'warning' : 'action'} icon={faRotate} />
        </Button>
    );
};

export default SeriesSeasonsButton;
