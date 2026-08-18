import './bootstrap';
import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';

Alpine.plugin(collapse);

import TomSelect from 'tom-select';

// Attach DataTables to jQuery and window
if (DataTable.use) {
    DataTable.use($);
}
window.$ = window.jQuery = $;
window.DataTable = DataTable;
window.TomSelect = TomSelect;
window.Alpine = Alpine;
window.Chart = Chart;

// Global Alpine sidebar store
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        collapsed: false,
        mobileOpen: false,
        toggle() {
            this.collapsed = !this.collapsed;
        },
        toggleMobile() {
            this.mobileOpen = !this.mobileOpen;
        },
        closeMobile() {
            this.mobileOpen = false;
        }
    });
});

Alpine.start();

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// Re-initialize after Alpine renders or dynamic updates
window.refreshIcons = () => {
    createIcons({ icons });
};
