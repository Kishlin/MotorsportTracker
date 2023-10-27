import { ReactNode } from 'react';

import AdminLayout from '../../src/Shared/Ui/Layout/Layout';

const Layout = ({ children }: { children: ReactNode }) => (
    <AdminLayout>
        {children}
    </AdminLayout>
);

export default Layout;
