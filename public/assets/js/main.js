// Main JavaScript file for Flyertrade Admin Panel
document.addEventListener('DOMContentLoaded', function () {
    console.log('Main.js loaded - Flyertrade Admin Panel');

    // Initialize all components
    initializeHeader();
    initializeModals();
    initializeDropdowns();
    initializeTables();

    console.log('All components initialized');
});

// Header functionality (basic - detailed handling in header-fix.js)
function initializeHeader() {
    // Close popups when clicking outside
    document.addEventListener("click", function (e) {
        const profilePopup = document.getElementById("profilePopup");
        const notifPopup = document.getElementById("notifPopup");

        // Close profile popup if clicking outside
        if (profilePopup && !e.target.closest("#profileBtn") && !e.target.closest("#profilePopup")) {
            profilePopup.style.display = "none";
        }

        // Close notification popup if clicking outside
        if (notifPopup && !e.target.closest("#notifBtn") && !e.target.closest("#notifPopup")) {
            notifPopup.style.display = "none";
        }
    });

    // Close buttons functionality
    document.querySelectorAll(".popup-close").forEach(btn => {
        btn.addEventListener("click", function () {
            const targetId = this.getAttribute("data-close");
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.style.display = "none";
            }
        });
    });
}

// Modal functionality
function initializeModals() {
    // Generic modal functions
    window.openModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "flex";
        }
    };

    window.closeModal = function (modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        }
    };

    // Close modals when clicking outside
    window.addEventListener('click', function (event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
}

// Dropdown functionality
function initializeDropdowns() {
    // Actions dropdown functionality
    document.querySelectorAll('.actions-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;

            // Close all other menus
            document.querySelectorAll('.actions-menu').forEach(m => {
                if (m !== menu) m.style.display = 'none';
            });

            // Toggle current menu
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function () {
        document.querySelectorAll('.actions-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });
}

// Table functionality
function initializeTables() {
    // Search functionality
    const searchInputs = document.querySelectorAll('.search-user, .search-input');
    searchInputs.forEach(input => {
        input.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.container').querySelector('.theme-table tbody');

            if (table) {
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const matches = text.includes(searchTerm);
                    row.style.display = matches ? '' : 'none';
                });
            }
        });
    });

    // Export to CSV functionality
    const exportBtns = document.querySelectorAll('.export-btn');
    exportBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            exportToCSV();
        });
    });
}

// Export to CSV function
function exportToCSV() {
    const table = document.querySelector('.theme-table');
    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tr'));
    let csv = [];

    rows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('td, th'));
        const rowData = cells.map(cell => {
            // Skip action cells and checkboxes
            if (cell.querySelector('.actions-dropdown') || cell.querySelector('input[type="checkbox"]')) {
                return '';
            }
            return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
        }).filter(cell => cell !== '""'); // Remove empty cells

        if (rowData.length > 0) {
            csv.push(rowData.join(','));
        }
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'export.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Utility functions
function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);