declare type UcFirst = (value: string) => string;

const ucFirst: UcFirst = (value) => value.charAt(0).toUpperCase() + value.slice(1);

export default ucFirst;
