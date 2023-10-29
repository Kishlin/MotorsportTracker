import { ReactNode } from 'react';

import AdminLayout from '../../src/Shared/Ui/Layout/Layout';
import { SeriesProvider } from '../../src/MotorsportAdmin/Series/Context/SeriesContext';

const Layout = ({ children }: { children: ReactNode }) => (
    <AdminLayout>
        <SeriesProvider>
            {children}
        </SeriesProvider>
    </AdminLayout>
);

export default Layout;
