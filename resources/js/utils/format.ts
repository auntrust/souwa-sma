export function seminarTypeLabel(type: string) {
    switch (type) {
        case 'onsite':
            return '現地';
        case 'online':
            return 'オンライン';
        case 'webinar':
            return 'ウェビナー';
        default:
            return type;
    }
}

export function isPaidLabel(isPaid: number) {
    return isPaid === 1 ? '有料' : '無料';
}

export function getDurationMinutes(
    start: string | null | undefined,
    end: string | null | undefined,
): number {
    // start または end が undefined, null, または空文字の場合は0を返す
    if (!start || !end) return 0;

    // "HH:mm" 形式を想定
    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    return eh * 60 + em - (sh * 60 + sm);
}

export function formatTime(time: string | null | undefined): string {
    // time が undefined, null, または空文字の場合は空文字を返す
    if (!time) return '';

    // "HH:mm:ss" → "HH:mm"
    return time.split(':').slice(0, 2).join(':');
}

export function formatDate(time: string): string {
    // "YYYY-MM-DD" → "YYYY.MM.DD"
    const date = time.split(' ')[0];
    const [yyyy, mm, dd] = date.split('-');
    return `${yyyy}.${mm}.${dd}`;
}

export function formatDateTimeAt(time: string): string {
    // "YYYY-MM-DD HH:mm:ss" → "YYYY.MM.DD HH:mm"
    const [date, t] = time.split(' ');
    if (!t) return time;
    const [yyyy, mm, dd] = date.split('-');
    const [hh, min] = t.split(':');
    return `${yyyy}.${mm}.${dd} ${hh}:${min}`;
}

export function formatDateTimeAtWithWeekday(time: string): string {
    // "YYYY-MM-DD HH:mm:ss" → "YYYY年MM月DD日（曜日） HH時MM分"
    const [date, t] = time.split(' ');
    if (!t) return time;

    const dateObj = new Date(date + ' ' + t);
    const weekdays = ['日', '月', '火', '水', '木', '金', '土'];

    const year = dateObj.getFullYear();
    const month = dateObj.getMonth() + 1;
    const day = dateObj.getDate();
    const weekday = weekdays[dateObj.getDay()];
    const [hh, min] = t.split(':');

    return `${year}年${String(month).padStart(2, '0')}月${String(day).padStart(2, '0')}日（${weekday}）${hh}時${min}分`;
}

export function formatDateWithWeekday(dateStr: string): string {
    const date = new Date(dateStr);
    const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    const w = weekdays[date.getDay()];
    return `${y}年${m}月${d}日（${w}）`;
}

export function nl2br(str: string) {
    if (!str) return '';
    return str.replace(/\r?\n/g, '<br>');
}

export function formatPostalCode(postalCode: string | number): string {
    const code = String(postalCode).replace(/[^\d]/g, '').padStart(7, '0');
    if (code.length !== 7) return String(postalCode);
    return `〒${code.slice(0, 3)}-${code.slice(3)}`;
}
