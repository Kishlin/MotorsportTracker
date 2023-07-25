export type Country = {
    id: string,
    code: string,
    name: string,
};

export type List<T> = {
    [key: string]: T,
};
