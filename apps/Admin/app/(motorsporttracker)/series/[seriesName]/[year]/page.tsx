import AdminBreadcrumbs from '../../../../../src/Shared/Ui/Breadcrumbs/AdminBreadcrumbs';
import AdminLink from '../../../../../src/Shared/Ui/Navigation/AdminLink';
import EventsTable from '../../../../../src/MotorsportAdmin/Events/Ui/EventsTable';

declare type PageParams = {
    seriesName: string,
    year: number,
};

const Page = async ({ params: { seriesName, year } } : { params: PageParams }) => (
    <>
        <AdminBreadcrumbs>
            <AdminLink to="/">Admin</AdminLink>
            <AdminLink to="/series">Series</AdminLink>
            <AdminLink to={`/series/${seriesName}`}>{decodeURI(seriesName)}</AdminLink>
            <AdminLink to={`/series/${seriesName}/${year}`}>{year}</AdminLink>
        </AdminBreadcrumbs>
        <EventsTable seriesName={seriesName} year={year} />
    </>
);

export default Page;
