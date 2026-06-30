import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

function normalizeNamaInputs() {
    document.querySelectorAll('input[name="nama"]').forEach((input) => {
        input.addEventListener('input', () => {
            input.value = input.value
                .toLowerCase()
                .replace(/\b\w/g, (m) => m.toUpperCase());
        });

        input.addEventListener('blur', () => {
            input.value = input.value
                .toLowerCase()
                .replace(/\b\w/g, (m) => m.toUpperCase());
        });
    });
}

document.addEventListener('DOMContentLoaded', normalizeNamaInputs);

Alpine.start();
