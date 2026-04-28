import './bootstrap';

const TOAST_DURATION = 3200;

const toastStyles = {
    success: 'border-[#24543B] bg-[#11291D] text-[#DDF8E5]',
    error: 'border-[#6B2B2B] bg-[#301515] text-[#FFE1E1]',
    info: 'border-[#244A66] bg-[#102230] text-[#D9F0FF]',
};

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

function showToast(payload) {
    if (!payload?.message) {
        return;
    }

    const root = document.getElementById('app-toast-root');
    if (!root) {
        return;
    }

    const tone = toastStyles[payload.level] ?? toastStyles.info;
    const toast = document.createElement('div');
    toast.className = `app-toast rounded-lg border px-4 py-3 shadow-lg ${tone}`;
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="mt-0.5 h-2.5 w-2.5 rounded-full bg-current/80"></div>
            <div class="min-w-0">
                <p class="text-sm font-semibold">${escapeHtml(payload.title ?? 'Notification')}</p>
                <p class="mt-1 text-sm opacity-90">${escapeHtml(payload.message)}</p>
            </div>
        </div>
    `;

    root.appendChild(toast);

    window.setTimeout(() => {
        toast.classList.add('is-leaving');
        window.setTimeout(() => toast.remove(), 180);
    }, payload.duration ?? TOAST_DURATION);
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.AppToast) {
        showToast(window.AppToast);
    }
});

window.addEventListener('app:toast', (event) => {
    showToast(event.detail ?? {});
});
