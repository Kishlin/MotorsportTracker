import { useRouter } from 'next/navigation';

declare type Navigate = {
    redirectionTo: (path: string) => () => void,
    navigate: (path: string) => void,
};

const useNavigate = (): Navigate => {
    const router = useRouter();

    const navigateTo = (path: string) => router.push(path);
    const redirection = (path: string) => () => router.push(path);

    return { navigate: navigateTo, redirectionTo: redirection };
};

export default useNavigate;
