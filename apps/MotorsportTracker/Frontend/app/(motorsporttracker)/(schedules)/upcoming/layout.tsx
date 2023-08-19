import { ReactNode, Suspense } from 'react';

import PageSkeleton from './skeleton';

const Layout = ({
    children,
}: {
    children: ReactNode
}) => (
    <Suspense fallback={<PageSkeleton />}>
        {children}
    </Suspense>
);

export default Layout;
