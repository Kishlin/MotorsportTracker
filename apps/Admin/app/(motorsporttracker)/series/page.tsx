import SeriesList from '../../../src/MotorsportAdmin/Series/Ui/SeriesTable/SeriesList';
import SeriesJobBar from '../../../src/MotorsportAdmin/Series/Ui/JobBar/SeriesJobBar';
import AdminBreadcrumbs from '../../../src/Shared/Ui/Breadcrumbs/AdminBreadcrumbs';
import AdminLink from '../../../src/Shared/Ui/Navigation/AdminLink';

const Page = async () => (
    <>
        <AdminBreadcrumbs>
            <AdminLink to="/">Admin</AdminLink>
            <AdminLink to="/series">Series</AdminLink>
        </AdminBreadcrumbs>
        <SeriesJobBar />
        <SeriesList />
    </>
);

export default Page;
