declare type TimeFormatter = (time: string, skipMillis?: boolean) => string;

const pad = (value: number|string, length: number) => `00${value}`.slice(-length);

const padAllButFirst = (value: number, index: number) => (0 === index ? value : pad(value, 2));

const formatTime: TimeFormatter = (time: string, skipMillis = false) => {
    let stime: string;
    if ('string' !== typeof time) {
        stime = String(time);
    } else {
        stime = time;
    }

    let milliseconds = 0;
    try {
        milliseconds = parseInt(stime.slice(-3), 10);
    } catch (err) {
        console.log(err);
    }

    let seconds = 0;
    const parts = [];
    try {
        seconds = 3 < stime.length ? parseInt(stime.slice(0, -3), 10) : 0;
    } catch (err) {
        console.log(err);
    }

    do {
        parts.push(seconds % 60);

        seconds = Math.floor(seconds / 60);
    } while (0 !== seconds);

    if (skipMillis) {
        return `${parts.reverse().map(padAllButFirst).join(':')}`;
    }

    return `${parts.reverse().map(padAllButFirst).join(':')}.${pad(milliseconds, 3)}`;
};

export default formatTime;
