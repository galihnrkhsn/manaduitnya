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
