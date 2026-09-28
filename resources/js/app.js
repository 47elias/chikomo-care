const menu = document.querySelector('#care-sidebar');
const openButton = document.querySelector('[data-menu-open]');
const backdrop = document.querySelector('.menu-backdrop');

function toggleMenu(open) {
    menu?.classList.toggle('is-open', open);
    openButton?.setAttribute('aria-expanded', String(open));
    if (backdrop) backdrop.hidden = !open;
    if (open) menu?.querySelector('[data-menu-close]')?.focus();
    else openButton?.focus();
}

openButton?.addEventListener('click', () => toggleMenu(true));
document.querySelectorAll('[data-menu-close]').forEach(button => button.addEventListener('click', () => toggleMenu(false)));
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') toggleMenu(false);
    if (event.key === 'Tab' && menu?.classList.contains('is-open')) {
        const items = [...menu.querySelectorAll('a, button')];
        const first = items[0];
        const last = items.at(-1);
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last?.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first?.focus(); }
    }
});

document.addEventListener('submit', event => {
    if (!event.target.matches('[data-busy-form]')) return;
    const button = event.target.querySelector('button[type="submit"]');
    button.disabled = true;
    button.dataset.originalLabel = button.textContent;
    button.textContent = button.dataset.busyLabel || 'Sending…';
});
window.addEventListener('pageshow', () => {
    document.querySelectorAll('[data-original-label]').forEach(button => {
        button.disabled = false;
        button.textContent = button.dataset.originalLabel;
    });
});

function scrollMessages() {
    document.querySelectorAll('[data-messages]').forEach(list => { list.scrollTop = list.scrollHeight; });
}
scrollMessages();

async function restoreLegacySession() {
    try {
        const token = localStorage.getItem('chikomo_token');
        if (!token) return false;
        const response = await fetch(document.body.dataset.restoreUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ token }),
        });
        if (response.ok || response.status === 404) {
            localStorage.removeItem('chikomo_token');
            localStorage.removeItem('counselor_room_status');
            localStorage.removeItem('counselor_local_messages');
        }
        if (response.ok) { window.location.reload(); return true; }
    } catch { /* Storage may be unavailable; the Laravel session still works. */ }
    return false;
}

async function pollCounselor() {
    const panel = document.querySelector('[data-counselor-sync]');
    if (!panel) return;
    let previousHtml;
    const state = document.querySelector('#counselor-state');
    const error = document.querySelector('#connection-error');
    const poll = async () => {
        if (document.hidden) { window.setTimeout(poll, 2500); return; }
        try {
            const response = await fetch(panel.dataset.counselorSync, { headers: { Accept: 'application/json' }, cache: 'no-store' });
            if (!response.ok) throw new Error('Unable to refresh the counselor connection. Retrying…');
            const data = await response.json();
            if (data.html !== previousHtml) {
                const list = state.querySelector('[data-messages]');
                const oldScroll = list?.scrollTop || 0;
                const atBottom = !list || list.scrollHeight - list.scrollTop - list.clientHeight < 80;
                state.innerHTML = data.html; // Escaped content rendered by the Blade partial.
                previousHtml = data.html;
                if (atBottom) scrollMessages();
                else { const updated = state.querySelector('[data-messages]'); if (updated) updated.scrollTop = oldScroll; }
            }
            document.querySelector('#counselor-form').hidden = !data.can_send;
            error.hidden = true;
            if (data.status === 'completed') return;
        } catch (exception) {
            error.textContent = exception.message;
            error.hidden = false;
        }
        window.setTimeout(poll, 2500);
    };
    poll();
}

restoreLegacySession().then(restoring => { if (!restoring) pollCounselor(); });
