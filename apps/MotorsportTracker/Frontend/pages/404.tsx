import { useRouter } from 'next/router';
import { useEffect } from 'react';

const RedirectToHome = () => {
    const router = useRouter();

    useEffect(
        () => {
            router.replace('/schedule');
        },
        [],
    );

    return null;
};

export default RedirectToHome;