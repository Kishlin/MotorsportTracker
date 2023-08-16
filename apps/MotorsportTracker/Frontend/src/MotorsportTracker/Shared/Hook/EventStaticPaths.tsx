import championships from '../../Config/Championships';
import seasonApi from '../../Event/Api/SeasonApi';
import { SeasonEvents } from '../Types';

export type EventPathParams = {
    params: {
        championship: string,
        year: string,
        event: string,
    },
};

export function useEventStaticPaths(): (
    () => Promise<{
        paths: Array<EventPathParams>,
        fallback: boolean|'blocking',
    }>
) {
    return async () => {
        const paramsPromise: Array<Promise<void|EventPathParams[]>> = [];
        const paths: Array<EventPathParams> = [];

        Object.keys(championships).forEach((slug: string) => {
            championships[slug].years.forEach(async (year: number) => {
                if (2015 <= year) {
                    paramsPromise.push(
                        seasonApi(slug, year)
                            .then((result: SeasonEvents) => Object.entries(result).map(([, seasonEvent]) => ({
                                params: {
                                    championship: slug,
                                    year: year.toString(),
                                    event: seasonEvent.slug,
                                },
                            }))),
                    );
                }
            });
        });

        (await Promise.all(paramsPromise)).forEach((paramsList: EventPathParams[]) => {
            paths.push(...paramsList);
        });

        return { paths, fallback: 'blocking' };
    };
}
