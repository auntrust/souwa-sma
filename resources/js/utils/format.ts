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
