import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
Alpine.start();

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// Re-initialize after Alpine renders or dynamic updates
window.refreshIcons = () => {
    createIcons({ icons });
};
