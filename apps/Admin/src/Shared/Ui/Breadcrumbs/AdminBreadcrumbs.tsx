'use client';

import { Breadcrumbs as MuiBreadcrumbs } from '@mui/material';
import { FunctionComponent, ReactNode } from 'react';

const AdminBreadcrumbs: FunctionComponent<{ children: ReactNode }> = ({ children }) => (
    <MuiBreadcrumbs>
        {children}
    </MuiBreadcrumbs>
);

export default AdminBreadcrumbs;
