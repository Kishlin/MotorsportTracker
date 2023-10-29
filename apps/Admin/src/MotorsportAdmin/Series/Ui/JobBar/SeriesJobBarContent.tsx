import { Alert, Button, Stack } from '@mui/material';
import { FunctionComponent } from 'react';

import { Job } from '../../../Shared/Types';

declare type SeriesJobBarContentProps = {
    onJobRequested: () => void,
    jobs: Array<Job>,
};

const SeriesJobBarContent: FunctionComponent<SeriesJobBarContentProps> = ({ jobs, onJobRequested }) => {
    if (0 === jobs.length) {
        return (
            <Button onClick={onJobRequested}>Scrap series</Button>
        );
    }

    const content = jobs.map((job: Job) => (
        <Alert key={job.id} variant="outlined" severity="info">
            {`Job ${job.status}: ${job.id} (${job.started_on})`}
        </Alert>
    ));

    return (
        <Stack spacing={2}>
            {content}
        </Stack>
    );
};

export default SeriesJobBarContent;
