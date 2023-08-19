import { ReactNode } from 'react';

import MotorsportTrackerMenu from '../../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';
import MotorsportTrackerLayout from '../../../src/Shared/Ui/Layout/Layout';

const Layout = ({
    children,
}: {
    children: ReactNode
}) => (
    <MotorsportTrackerLayout menu={<MotorsportTrackerMenu />}>
        {children}
    </MotorsportTrackerLayout>
);

export default Layout;
