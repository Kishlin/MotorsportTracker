export type Country = {
    id: string,
    code: null|string,
    name: string,
};

export type List<T> = {
    [key: string]: T,
};
