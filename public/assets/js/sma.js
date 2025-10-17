// ?sma_c= の値を取得（前後のスラッシュは除去）
function getSmaCFromQuery(paramName = 'sma_c') {
    const params = new URLSearchParams(window.location.search);
    const val = params.get(paramName);
    return val ? String(val).replace(/^\/+|\/+$/g, '') : null;
}

// URL 末尾のパスとして segment を追加（クエリ/ハッシュの前に入れる）。二重追加は回避
function appendSegmentToUrlPath(url, segment) {
    if (!url || !segment) return url;
    if (/^(mailto:|tel:|javascript:)/i.test(url)) return url;

    try {
        const u = new URL(url, window.location.origin);

        // 既に末尾が同じなら何もしない
        const parts = u.pathname.split('/').filter(Boolean);
        const last = parts[parts.length - 1];
        if (last === segment) return u.toString();

        if (!u.pathname.endsWith('/')) u.pathname += '/';
        u.pathname += segment;
        return u.toString();
    } catch {
        // フォールバック（相対URLなど）
        const str = String(url);
        const [beforeHash, hash = ''] = str.split('#');
        const [pathOnly, query = ''] = beforeHash.split('?');
        if (
            pathOnly.endsWith('/' + segment) ||
            pathOnly.endsWith('/' + segment + '/')
        ) {
            return url;
        }
        const sep = pathOnly.endsWith('/') ? '' : '/';
        const newBase = `${pathOnly}${sep}${segment}${query ? '?' + query : ''}`;
        return `${newBase}${hash ? '#' + hash : ''}`;
    }
}

// class="SmaGotoSeminar" の a タグのみ href を置き換え
function updateAnchorsWithSmaCForClass(className = 'SmaGotoSeminar') {
    const c = getSmaCFromQuery();
    if (!c) return;

    document.querySelectorAll(`a.${className}[href]`).forEach((a) => {
        const href = a.getAttribute('href');
        if (!href) return;
        const updated = appendSegmentToUrlPath(href, c);
        if (updated && updated !== href) {
            a.setAttribute('href', updated);
        }
    });
}

// ページ読み込み時に自動適用
document.addEventListener('DOMContentLoaded', () => {
    updateAnchorsWithSmaCForClass('SmaGotoSeminar');
});
