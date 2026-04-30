export function useRelativeTime() {
    const formatRelative = (value: string | null | undefined): string => {
        if (!value) return '';
        const date = new Date(value);
        const now = new Date();
        const diffSeconds = Math.floor((date.getTime() - now.getTime()) / 1000);
        const ranges: Array<[number, Intl.RelativeTimeFormatUnit]> = [
            [60, 'second'],
            [60, 'minute'],
            [24, 'hour'],
            [30, 'day'],
            [12, 'month'],
            [Number.POSITIVE_INFINITY, 'year'],
        ];

        let duration = diffSeconds;
        let unit: Intl.RelativeTimeFormatUnit = 'second';

        for (const [amount, nextUnit] of ranges) {
            if (Math.abs(duration) < amount) {
                unit = nextUnit;
                break;
            }
            duration = Math.round(duration / amount);
        }

        return new Intl.RelativeTimeFormat('id-ID', { numeric: 'auto' }).format(duration, unit);
    };

    const formatDate = (value: string | null | undefined): string => {
        if (!value) return '';
        return new Date(value).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    return { formatRelative, formatDate };
}
