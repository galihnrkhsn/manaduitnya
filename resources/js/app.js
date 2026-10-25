import '@hotwired/turbo';
import './bootstrap';
import Alpine from 'alpinejs';
import '@fontsource-variable/inter';
import { registerSW } from 'virtual:pwa-register';

window.Alpine = Alpine;
Alpine.start();

if ('serviceWorker' in navigator) {
    registerSW({ immediate: true });
}

const isStandalone =
    window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

if (isStandalone) {
    document.addEventListener('contextmenu', (e) => {
        if (e.target.closest('a, button, nav')) e.preventDefault();
    });
}
