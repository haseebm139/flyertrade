// Main JavaScript file for Flyertrade Admin Panel
if (window.mainInitialized) {
    // Script already initialized, exit
} else {
    window.mainInitialized = true;

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
    if (!document.querySelector('#main-js-styles')) {
        const style = document.createElement('style');
        style.id = 'main-js-styles';
        style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
        document.head.appendChild(style);
    }


    // Disable zoom (Ctrl + scroll / + / -)
    document.addEventListener('wheel', function (e) {
        if (e.ctrlKey) e.preventDefault();
    }, { passive: false });

    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) &&
            (e.key === '+' || e.key === '-' || e.key === '=' || e.key === '0')) {
            e.preventDefault();
        }
    });
    if (!window.actionsInitialized) {
        const actions = document.querySelector('.toolbar-actions');
        const anyChecked = () => [...document.querySelectorAll('.row-check')].some(c => c.checked);
        const toggleActions = () => actions.hidden = !anyChecked();

        document.addEventListener('change', e => {
            if (e.target.classList.contains('row-check')) toggleActions();
        });
        window.actionsInitialized = true;
    }

    // Badge functionality
    function setBadge(badge, state) {
        badge.classList.remove('badge-verified', 'badge-declined', 'badge-pending');
        if (state === 'verified') { badge.classList.add('badge-verified'); badge.textContent = 'Verified'; }
        if (state === 'declined') { badge.classList.add('badge-declined'); badge.textContent = 'Declined'; }
        if (state === 'pending') { badge.classList.add('badge-pending'); badge.textContent = 'Pending'; }
    }

    document.addEventListener('click', e => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const state = btn.getAttribute('data-action');
        document.querySelectorAll('.row-check:checked').forEach(chk => {
            const badge = chk.closest('.doc-row').querySelector('[data-badge]');
            setBadge(badge, state);
            chk.checked = false;
        });
        toggleActions();
    });
    if (!window.serviceModalInitialized) {
        const serviceModal = document.getElementById("service-details-modal");
        const openServiceBtn = document.getElementById("openServiceDetails");
        const closeServiceBtn = document.getElementById("closeServiceDetails");

        if (openServiceBtn && serviceModal) {
            openServiceBtn.onclick = () => {
                serviceModal.style.display = "flex";
            };
        }

        if (closeServiceBtn && serviceModal) {
            closeServiceBtn.onclick = () => {
                serviceModal.style.display = "none";
            };
        }

        if (serviceModal) {
            window.addEventListener('click', (e) => {
                if (e.target === serviceModal) {
                    serviceModal.style.display = "none";
                }
            });
        }
        window.serviceModalInitialized = true;
    }
    (function () {
        if (window.__CHECK_MODAL_INIT__) return;
        window.__CHECK_MODAL_INIT__ = true;

        const modal = document.getElementById('check-modal');
        if (!modal) return;
        const backdrop = modal.querySelector('.cm-backdrop');
        const closeBt = modal.querySelector('.cm-close');
        const titleEl = modal.querySelector('#cm-title');
        const imgEl = modal.querySelector('#cm-img');
        const phEl = modal.querySelector('#cm-ph');

        function openCheckModal(title, src) {
            titleEl.textContent = title || 'Document';
            if (src) {
                imgEl.hidden = false; imgEl.src = src;
                phEl.hidden = true;
                imgEl.onerror = () => { imgEl.hidden = true; phEl.hidden = false; };
            } else {
                imgEl.hidden = true; imgEl.removeAttribute('src'); phEl.hidden = false;
            }
            modal.classList.add('is-open');
            document.body.classList.add('cm-lock');
        }

        function closeCheckModal() {
            modal.classList.remove('is-open');
            document.body.classList.remove('cm-lock');
        }

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-check-modal]');
            if (!btn) return;
            e.preventDefault();
            openCheckModal(btn.getAttribute('data-title'), btn.getAttribute('data-src'));
        });

        // Close controls
        if (backdrop) backdrop.addEventListener('click', closeCheckModal);
        if (closeBt) closeBt.addEventListener('click', closeCheckModal);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) closeCheckModal();
        });
    })();

} // End of initialization check