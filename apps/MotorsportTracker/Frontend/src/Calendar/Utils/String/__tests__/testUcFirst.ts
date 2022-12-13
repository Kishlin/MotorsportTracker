import ucFirst from '../ucFirst';

it('converts the first letter to upper case', () => {
    expect(ucFirst('hello')).toEqual('Hello');
});

it('only converts the first word', () => {
    expect(ucFirst('hello world')).toEqual('Hello world');
});

it('does not alter an existing uppercase letter', () => {
    expect(ucFirst('Hello')).toEqual('Hello');
});
