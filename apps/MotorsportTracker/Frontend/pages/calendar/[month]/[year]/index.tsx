// @ts-ignore
import React from 'react';
import { useRouter } from 'next/router';

import Calendar from '../../../../src/Calendar/Component/Calendar';
import Layout from '../../../../src/Shared/Ui/Layout/Layout';

const CalendarPage: React.FunctionComponent = () => {
    const router = useRouter();
    const { month, year } = router.query;

    if (undefined === month || undefined === year) {
        return <noscript />;
    }

    const date = new Date(Date.parse(`${month} 1, ${year}`));

    return (
        <Layout>
            <Calendar date={date} />
        </Layout>
    );
};

export default CalendarPage;
