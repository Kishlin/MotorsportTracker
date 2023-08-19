import { FunctionComponent } from 'react';

import ScheduleTitleUpcoming from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleTitleUpcoming';
import ScheduleContainer from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleContainer';
import ScheduleSkeleton from '../../../src/MotorsportTracker/Schedule/Ui/ScheduleSkeleton';

const PageSkeleton: FunctionComponent = () => (
    <ScheduleContainer>
        <ScheduleTitleUpcoming />
        <ScheduleSkeleton />
    </ScheduleContainer>
);

export default PageSkeleton;
