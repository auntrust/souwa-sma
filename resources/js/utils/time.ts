// 時刻フォーマット関数
export function formatTime(time?: string): string {
    if (!time) return '';
    // 例: "09:30:00" → "09:30"
    return time.slice(0, 5);
}

// 開始時刻と終了時刻から分数を計算
export function calcDuration(start?: string, end?: string): string {
    if (!start || !end) return '';
    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    const startMinutes = sh * 60 + sm;
    const endMinutes = eh * 60 + em;
    const diff = endMinutes - startMinutes;
    return diff > 0 ? `${diff}分` : '';
}

// 今日の日付を取得する
export function getTodayJST(): string {
    const now = new Date();
    // JSTに変換
    now.setHours(now.getHours() + 9);
    return now.toISOString().slice(0, 10);
}
