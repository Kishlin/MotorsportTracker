import { faAngleRight } from '@fortawesome/free-solid-svg-icons/faAngleRight';
import { faAngleLeft } from '@fortawesome/free-solid-svg-icons/faAngleLeft';
import { Grid, Typography } from '@mui/material';
import React from 'react';

import FontAwesomeSvgIcon from '../../../Shared/Ui/Icon/FontAwesomeSvgIcon';
import computeScheduleUri from '../Utils/Navigation/computeScheduleUri';
import formatDateForHeader from '../Utils/Date/formatDateForHeader';
import Link from '../../../Shared/Ui/Navigation/Link';
import addMonths from '../Utils/Date/addMonths';

declare type ScheduleNavigationProps = {
    date: Date,
}

const ScheduleNavigation: React.FunctionComponent<ScheduleNavigationProps> = ({ date }) => (
    <Grid item container direction="row" sx={{ mb: 2 }}>
        <Grid item md={5} sm={4} xs={3} container justifyContent="flex-end">
            <Link to={computeScheduleUri(addMonths(date, -1))}>
                <FontAwesomeSvgIcon icon={faAngleLeft} />
            </Link>
        </Grid>
        <Grid item md={2} sm={4} xs={6}>
            <Typography align="center">{formatDateForHeader(date)}</Typography>
        </Grid>
        <Grid item md={5} sm={4} xs={3}>
            <Link to={computeScheduleUri(addMonths(date, +1))}>
                <FontAwesomeSvgIcon icon={faAngleRight} />
            </Link>
        </Grid>
    </Grid>
);

export default ScheduleNavigation;
