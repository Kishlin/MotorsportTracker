declare type TimeFormatter = (time: string) => string;

const pad = (value: number|string, length: number) => `00${value}`.slice(-length);

const padAllButFirst = (value: number, index: number) => (0 === index ? value : pad(value, 2));

const formatTime: TimeFormatter = (time) => {
    if ('0' === time) {
        return '';
    }

    const milliseconds = parseInt(time.slice(-3), 10);

    const parts = [];
    let seconds = 3 < time.length ? parseInt(time.slice(0, -3), 10) : 0;

    do {
        parts.push(seconds % 60);

        seconds = Math.floor(seconds / 60);
    } while (0 !== seconds);

    return `${parts.reverse().map(padAllButFirst).join(':')}.${pad(milliseconds, 3)}`;
};

export default formatTime;
