import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';

// Alpine Store untuk Toast
document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        show: false,
        message: '',
        type: 'success',

        showToast(message, type = 'success') {
            this.message = message;
            this.type = type;
            this.show = true;
        }
    });
});

window.Alpine = Alpine;
Alpine.start();
