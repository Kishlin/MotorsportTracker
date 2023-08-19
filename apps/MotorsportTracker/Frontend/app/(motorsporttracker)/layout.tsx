import { ReactNode } from 'react';

import MotorsportTrackerLayout from '../../src/Shared/Ui/Layout/Layout';
import MotorsportTrackerMenu from '../../src/MotorsportTracker/Menu/Ui/MotorsportTrackerMenu';

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
