import {
    FunctionComponent,
    createContext,
    useCallback,
    ReactNode,
    useEffect,
    useState,
} from 'react';

export type HydrationContextType = (ifHydrated: ReactNode, ifNotHydrated?: ReactNode) => ReactNode;

export const HydrationContext = createContext<HydrationContextType>(undefined);

export const HydrationProvider: FunctionComponent<{ children: ReactNode }> = ({ children }) => {
    const [hydrated, setHydrated] = useState<boolean>(false);

    useEffect(
        () => {
            setHydrated(true);
        },
        [],
    );

    const render: HydrationContextType = useCallback<HydrationContextType>(
        (ifHydrated, ifNotHydrated = <noscript />) => (
            hydrated ? ifHydrated : ifNotHydrated
        ),
        [hydrated],
    );

    return (
        <HydrationContext.Provider value={render}>
            {children}
        </HydrationContext.Provider>
    );
};
