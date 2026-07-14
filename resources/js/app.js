import './bootstrap';

const pad = (value) => String(value).padStart(2, '0');

function setupInfusionCountdown() {
    const panel = document.querySelector('[data-infusion-countdown]');

    if (!panel) {
        return;
    }

    const volumeInput = document.querySelector('#initial_volume');
    const installedAtInput = document.querySelector('#installed_at');
    const hoursNode = panel.querySelector('[data-countdown-hours]');
    const minutesNode = panel.querySelector('[data-countdown-minutes]');
    const secondsNode = panel.querySelector('[data-countdown-seconds]');
    const percentNode = panel.querySelector('[data-countdown-percent]');
    const baseMinutes = Number(panel.dataset.baseMinutes || 272);
    const baseVolume = 500;

    let targetTime = null;
    let totalSeconds = baseMinutes * 60;

    const recalculateTarget = () => {
        const volume = Math.max(1, Number(volumeInput?.value || baseVolume));
        const installedAt = installedAtInput?.value ? new Date(installedAtInput.value) : new Date();
        const durationMinutes = Math.max(1, Math.round(baseMinutes * (volume / baseVolume)));

        totalSeconds = durationMinutes * 60;
        targetTime = new Date(installedAt.getTime() + totalSeconds * 1000);

        if (Number.isNaN(targetTime.getTime()) || targetTime.getTime() <= Date.now()) {
            targetTime = new Date(Date.now() + totalSeconds * 1000);
        }
    };

    const render = () => {
        if (!targetTime) {
            recalculateTarget();
        }

        const remainingSeconds = Math.max(0, Math.floor((targetTime.getTime() - Date.now()) / 1000));
        const hours = Math.floor(remainingSeconds / 3600);
        const minutes = Math.floor((remainingSeconds % 3600) / 60);
        const seconds = remainingSeconds % 60;
        const remainingRatio = totalSeconds > 0 ? remainingSeconds / totalSeconds : 0;
        const percent = Math.max(0, Math.min(100, Math.round(remainingRatio * 100)));

        if (hoursNode) hoursNode.textContent = pad(hours);
        if (minutesNode) minutesNode.textContent = pad(minutes);
        if (secondsNode) secondsNode.textContent = pad(seconds);
        if (percentNode) percentNode.textContent = `${percent}%`;
    };

    volumeInput?.addEventListener('input', () => {
        recalculateTarget();
        render();
    });

    installedAtInput?.addEventListener('input', () => {
        recalculateTarget();
        render();
    });

    recalculateTarget();
    render();
    window.setInterval(render, 1000);
}

function secondsFromHms(value) {
    const parts = String(value || '')
        .trim()
        .split(':')
        .map((part) => Number(part));

    if (parts.length !== 3 || parts.some((part) => Number.isNaN(part))) {
        return null;
    }

    return (parts[0] * 3600) + (parts[1] * 60) + parts[2];
}

function formatHms(totalSeconds) {
    const seconds = Math.max(0, Math.floor(totalSeconds));
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;

    return `${pad(hours)}:${pad(minutes)}:${pad(remainingSeconds)}`;
}

function setupLiveCountdowns() {
    const nodes = [...document.querySelectorAll('[data-live-countdown]')];

    if (nodes.length === 0) {
        return;
    }

    const counters = nodes
        .map((node) => ({
            node,
            remainingSeconds: secondsFromHms(node.dataset.liveCountdown || node.textContent),
        }))
        .filter((counter) => counter.remainingSeconds !== null);

    const render = () => {
        counters.forEach((counter) => {
            counter.node.textContent = formatHms(counter.remainingSeconds);
        });
    };

    render();

    window.setInterval(() => {
        counters.forEach((counter) => {
            counter.remainingSeconds = Math.max(0, counter.remainingSeconds - 1);
        });
        render();
    }, 1000);
}

function setupLiveClock() {
    const nodes = [...document.querySelectorAll('[data-live-clock]')];

    if (nodes.length === 0) {
        return;
    }

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const render = () => {
        const now = new Date();
        const label = `${pad(now.getDate())} ${monthNames[now.getMonth()]} ${now.getFullYear()}, ${pad(now.getHours())}:${pad(now.getMinutes())}`;

        nodes.forEach((node) => {
            node.textContent = `${label} WIT`;
        });
    };

    render();
    window.setInterval(render, 1000);
}

function setupTopbarDropdowns() {
    const dropdowns = [...document.querySelectorAll('[data-topbar-dropdown]')];

    if (dropdowns.length === 0) {
        return;
    }

    dropdowns.forEach((dropdown) => {
        dropdown.addEventListener('toggle', () => {
            if (!dropdown.open) {
                return;
            }

            dropdowns.forEach((otherDropdown) => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.open = false;
                }
            });
        });
    });

    document.addEventListener('click', (event) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown.open && !dropdown.contains(event.target)) {
                dropdown.open = false;
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        dropdowns.forEach((dropdown) => {
            dropdown.open = false;
        });
    });
}

function setupPasswordToggles() {
    const toggles = [...document.querySelectorAll('[data-password-toggle]')];

    if (toggles.length === 0) {
        return;
    }

    toggles.forEach((toggle) => {
        const wrapper = toggle.parentElement;
        const input = wrapper?.querySelector('[data-password-input]');

        if (!input) {
            return;
        }

        toggle.addEventListener('click', () => {
            const isHidden = input.getAttribute('type') === 'password';

            input.setAttribute('type', isHidden ? 'text' : 'password');
            toggle.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    });
}

document.addEventListener('DOMContentLoaded', setupInfusionCountdown);
document.addEventListener('DOMContentLoaded', setupLiveCountdowns);
document.addEventListener('DOMContentLoaded', setupLiveClock);
document.addEventListener('DOMContentLoaded', setupTopbarDropdowns);
document.addEventListener('DOMContentLoaded', setupPasswordToggles);
