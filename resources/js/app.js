import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js');
}
