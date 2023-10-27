import { ReactNode } from 'react';
import AdminBreadcrumbs from '../../../src/Shared/Ui/Breadcrumbs/AdminBreadcrumbs';
import AdminLink from '../../../src/Shared/Ui/Navigation/AdminLink';

const Layout = ({ children }: { children: ReactNode }) => (
    <>
        <AdminBreadcrumbs>
            <AdminLink to="/">Admin</AdminLink>
            <AdminLink to="/series">Series</AdminLink>
        </AdminBreadcrumbs>
        {children}
    </>
);

export default Layout;
