import { useRouter } from 'next/router';

declare type Navigate = {
    redirectionTo: (path: string) => () => void,
    navigate: (path: string) => void,
};

const useNavigate = (): Navigate => {
    const router = useRouter();

    const navigateTo = (path: string) => router.replace(path);
    const redirection = (path: string) => () => router.replace(path);

    return { navigate: navigateTo, redirectionTo: redirection };
};

export default useNavigate;