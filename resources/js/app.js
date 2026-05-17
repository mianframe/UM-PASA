import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || form.method.toLowerCase() !== 'post') {
        return;
    }

    if (event.defaultPrevented) {
        return;
    }

    if (!form.checkValidity() || form.dataset.allowDoubleSubmit === 'true') {
        return;
    }

    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((submitter) => {
        submitter.disabled = true;
        submitter.setAttribute('aria-busy', 'true');
        submitter.classList.add('cursor-wait', 'opacity-70');

        if (submitter instanceof HTMLButtonElement && !submitter.dataset.loadingLocked) {
            submitter.dataset.loadingLocked = 'true';
            submitter.dataset.originalLabel = submitter.innerHTML;
            submitter.textContent = submitter.dataset.loadingLabel || 'Processing...';
        }
    });
});
