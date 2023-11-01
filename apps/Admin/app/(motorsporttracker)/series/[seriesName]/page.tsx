import AdminBreadcrumbs from '../../../../src/Shared/Ui/Breadcrumbs/AdminBreadcrumbs';
import AdminLink from '../../../../src/Shared/Ui/Navigation/AdminLink';
import SeasonsTable from '../../../../src/MotorsportAdmin/Seasons/Ui/SeasonsTable';

declare type PageParams = {
    seriesName: string,
};

const Page = async ({ params: { seriesName } } : { params: PageParams }) => {
    return (
        <>
            <AdminBreadcrumbs>
                <AdminLink to="/">Admin</AdminLink>
                <AdminLink to="/series">Series</AdminLink>
                <AdminLink to={`/series/${seriesName}`}>{decodeURI(seriesName)}</AdminLink>
            </AdminBreadcrumbs>
            <SeasonsTable seriesName={seriesName} />
        </>
    );
};

export default Page;
