import './bootstrap';
import Alpine from 'alpinejs';
import { icons } from 'lucide';

// Initialize Alpine.js
window.Alpine = Alpine;

// Register Lucide icons
window.lucideIcons = icons;

// Custom components
Alpine.data('dropdown', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.data('modal', () => ({
    open: false,
    show() {
        this.open = true;
    },
    hide() {
        this.open = false;
    }
}));

Alpine.data('tabs', (defaultTab = 'tab-1') => ({
    activeTab: defaultTab,
    setActiveTab(tab) {
        this.activeTab = tab;
    }
}));

Alpine.data('sidebar', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    }
}));

Alpine.start();

// Lucide icons initialization
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
