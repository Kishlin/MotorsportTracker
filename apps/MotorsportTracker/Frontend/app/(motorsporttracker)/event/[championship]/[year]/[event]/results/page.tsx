// noinspection JSUnusedGlobalSymbols

import ResultsContent from '../../../../../../../src/MotorsportTracker/Result/Ui/ResultsContent';
import EventContainer from '../../../../../../../src/MotorsportTracker/Event/Ui/EventContainer';
import resultsApi from '../../../../../../../src/MotorsportTracker/Result/Api/ResultsApi';
import seasonApi from '../../../../../../../src/MotorsportTracker/Event/Api/SeasonApi';

declare type PageParams = {
    championship: string,
    event: string,
    year: string,
};

const Page = async ({ params: { championship, year, event } }: { params: PageParams }) => {
    const season = await seasonApi(championship, parseInt(year, 10));

    const eventId = season[event].id;
    const results = await resultsApi(eventId);

    return (
        <EventContainer>
            <ResultsContent results={results} />
        </EventContainer>
    );
};

export default Page;
