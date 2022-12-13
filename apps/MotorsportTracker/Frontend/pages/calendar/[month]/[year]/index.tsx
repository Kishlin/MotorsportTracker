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

    return (
        <Layout>
            <Calendar
                month={month as string}
                year={parseInt(year as string, 10)}
            />
        </Layout>
    );
};

export default CalendarPage;
