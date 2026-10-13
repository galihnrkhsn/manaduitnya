import './bootstrap';
import Alpine from 'alpinejs';
import '@fontsource-variable/inter';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js');
}
