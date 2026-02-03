// Main JavaScript file for Flyertrade Admin Panel
document.addEventListener('DOMContentLoaded', function () {


    // Initialize all components
    initializeHeader();
    initializeModals();
    initializeDropdowns();
    initializeTables();


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
        // Skip if button has wire:click (Livewire handles it) or is inside Livewire component
        if (btn.closest('[wire\\:id]') || btn.hasAttribute('wire:click') || btn.getAttribute('wire:click')) {
            return;
        }
        btn.addEventListener('click', function (e) {
            e.preventDefault();
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
const actions = document.querySelector('.toolbar-actions');
const anyChecked = () => [...document.querySelectorAll('.row-check')].some(c => c.checked);
const toggleActions = () => actions.hidden = !anyChecked();

document.addEventListener('change', e => {
    if (e.target.classList.contains('row-check')) toggleActions();
});

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






document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.nav-link').forEach(link => {
        const icon = link.querySelector('.nav-icon');
        const defaultIcon = link.getAttribute('data-icon-default');
        const activeIcon = link.getAttribute('data-icon-active');

        if (link.classList.contains('active') && activeIcon) {
            icon.src = activeIcon;
        } else if (defaultIcon) {
            icon.src = defaultIcon;
        }

        // optional hover zoom
        link.addEventListener('mouseenter', () => {
            icon.style.transform = 'scale(1.1)';
        });
        link.addEventListener('mouseleave', () => {
            icon.style.transform = link.classList.contains('active') ? 'scale(1.1)' : 'scale(1)';
        });
    });
});


// message hide and show
// ------------------- Send Message -------------------
const sendBtn = document.getElementById('sendBtn');
const chatInput = document.getElementById('chatInput');
const chatBody = document.getElementById('chatBody');

if (sendBtn && chatInput && chatBody) {
    sendBtn.addEventListener('click', () => {
        const msg = chatInput.value.trim();
        if (msg === '') return;

        const newMsg = document.createElement('div');
        newMsg.classList.add('message', 'message-right');
        newMsg.innerHTML =
            `<p>${msg}</p>
             <span class="timestamp">
                 Message sent ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
             </span>`;

        chatBody.appendChild(newMsg);
        chatInput.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;
    });

    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendBtn.click();
    });
}

// ------------------- Main Panels -------------------
document.addEventListener("DOMContentLoaded", function () {
    const contentPanel = document.querySelector(".content-panel");
    const emailCompose = document.querySelector(".email-compose");
    const viewEmail = document.querySelector(".view-email");
    const messageChat = document.querySelector(".message-chat-theme");

    const newMsgBtn = document.querySelector(".export-btn");
    const userListItems = document.querySelectorAll(".user-list-item");
    const tabButtons = [...document.querySelectorAll(".filter-btn")]
        .filter(btn => !btn.closest('[data-livewire-tabs="true"]'));

    // âœ… Select ALL buttons with class .new-email-btn (handles both)
    const newEmailBtns = document.querySelectorAll(".new-email-btn");

    // Hide all panels
    function hideAll() {
        [contentPanel, emailCompose, viewEmail, messageChat].forEach(el => {
            if (el) el.style.display = "none";
        });
    }

    // Show a specific panel
    function show(el) {
        hideAll();
        if (el) el.style.display = "flex";
    }

    // Default view
    show(contentPanel);

    // ðŸŸ¢ Open Compose Email on any new-email-btn click
    newEmailBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            show(emailCompose);
        });
    });

    // ðŸŸ¢ Open Chat on new message button
    if (newMsgBtn) {
        newMsgBtn.addEventListener("click", () => {
            show(messageChat);
        });
    }

    // ðŸŸ¢ When user clicks an email list item â†’ open email view
    document.querySelectorAll("#emails .user-list-item").forEach(item => {
        item.addEventListener("click", () => {
            show(viewEmail);
        });
    });

    // ðŸŸ¢ When user clicks a chat list item â†’ open chat
    document.querySelectorAll("#chats .user-list-item").forEach(item => {
        item.addEventListener("click", () => {
            show(messageChat);
        });
    });

    // ðŸŸ¢ Filter buttons active state + show content panel
    tabButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            tabButtons.forEach(b => b.classList.remove("tab-active"));
            btn.classList.add("tab-active");
            show(contentPanel);
        });
    });
});


// Select all functionality
const selectAll = document.getElementById("selectAll");
const userCheckboxes = document.querySelectorAll(".select-user");

if (selectAll && selectAll.dataset.livewireSelect === "true") {
    // Livewire handles selection state.
} else if (selectAll) {
    selectAll.addEventListener("change", function () {
        userCheckboxes.forEach(cb => cb.checked = this.checked);
    });

    userCheckboxes.forEach(cb => {
        cb.addEventListener("change", () => {
            const allChecked = [...userCheckboxes].every(c => c.checked);
            selectAll.checked = allChecked;
        });
    });
}

// Filter functionality
const filterStatus = document.getElementById("filterStatus");
filterStatus.addEventListener("change", function () {
    const value = this.value;
    const users = document.querySelectorAll(".user-list-item");

    users.forEach(user => {
        if (value === "all" || user.classList.contains(value)) {
            user.style.display = "flex";
        } else {
            user.style.display = "none";
        }
    });
});
function toggleDropdown(el) {
    const parent = el.closest('.status-dropdown');
    const dropdown = parent.querySelector('.dropdown-menu');
    const isOpen = dropdown.style.display === 'block';

    // Prevent outside listener from firing
    event.stopPropagation();

    // Hide all dropdowns first
    document.querySelectorAll('.dropdown-menu').forEach(d => d.style.display = 'none');
    document.querySelectorAll('.status').forEach(s => s.classList.remove('open'));

    // Toggle current dropdown
    if (!isOpen) {
        dropdown.style.display = 'block';
        el.classList.add('open');
    } else {
        dropdown.style.display = 'none';
        el.classList.remove('open');
    }
}

// --- CLOSE ON OUTSIDE CLICK ---
document.addEventListener('click', function () {
    document.querySelectorAll('.dropdown-menu').forEach(d => d.style.display = 'none');
    document.querySelectorAll('.status').forEach(s => s.classList.remove('open'));
});

// --- PREVENT dropdown-menu click FROM closing ---
document.addEventListener('click', function (e) {
    if (e.target.closest('.dropdown-menu') || e.target.closest('.status')) {
        e.stopPropagation();
    }
}, true);


function setStatus(el, status) {
    const parent = el.closest('.status-dropdown');
    const statusBtn = parent.querySelector('.status');

    // Reset old classes
    statusBtn.classList.remove('publish', 'unpublished', 'pending', 'open', 'Resolved', 'Unresolved');

    // Define colors
    let color = '';
    let cssClass = '';

    if (status === 'Resolved') {
        color = '#0a8754'; // Green
        cssClass = 'publish';
    } if (status === 'Publish') {
        color = '#0a8754'; // Green
        cssClass = 'publish';
    } else if (status === 'Unpublished') {
        color = '#D00416'; // Red
        cssClass = 'unpublished';
    } else if (status === 'Pending') {
        color = '#d4aa00'; // Yellow
        cssClass = 'pending';
    }
    else if (status === 'Unresolved') {
        color = '#D00416'; // Yellow
        cssClass = 'unpublished';
    }
    // alert(cssClass);
    // Apply new class and color
    statusBtn.classList.add(cssClass);
    statusBtn.style.color = color;

    // Update button text + arrow
    statusBtn.innerHTML = `${status}
    <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" 
      viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2" 
      stroke-linecap="round" stroke-linejoin="round">
      <polyline points="6 9 12 15 18 9"></polyline>
    </svg>`;

    // Close dropdown
    parent.querySelector('.dropdown-menu').style.display = 'none';
}

// Wait for DOM to fully load
document.addEventListener("DOMContentLoaded", function () {

    // ===== Notification Popup =====
    const notifBtn = document.getElementById('notifBtn');
    const notifPopup = document.getElementById('notifPopup');

    notifBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        notifPopup.style.display = notifPopup.style.display === 'block' ? 'none' : 'block';
    });

    // Hide notif popup when clicking outside
    document.addEventListener('click', function (e) {
        if (!notifBtn.contains(e.target)) {
            notifPopup.style.display = 'none';
        }
    });

    // ===== Provider Modal =====
    const providerModal = document.getElementById('providerModal');

    // Log check (optional)
    console.log("Modal found:", providerModal);

    // Handle all buttons that open provider modal
    document.querySelectorAll('[data-modal="providerModal"]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("View clicked!"); // Debug check
            notifPopup.style.display = 'none'; // hide popup
            providerModal.style.display = 'flex'; // show modal
            providerModal.style.animation = 'fadeIn 0.2s ease';
        });
    });

    // Close provider modal (cross button)
    document.querySelectorAll('[data-close="providerModal"]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            providerModal.style.display = 'none';
        });
    });

    // Close modal if clicking outside
    window.addEventListener('click', function (e) {
        if (e.target === providerModal) {
            providerModal.style.display = 'none';
        }
    });

});


function toggleDropdown(el) {
    const parent = el.closest('.status-dropdown');
    const dropdown = parent.querySelector('.dropdown-menu');
    const isOpen = dropdown.style.display === 'block';

    // Band kar sab dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(d => d.style.display = 'none');
    document.querySelectorAll('.status').forEach(s => s.classList.remove('open'));

    // Toggle current
    if (!isOpen) {
        dropdown.style.display = 'block';
        el.classList.add('open');
    } else {
        dropdown.style.display = 'none';
        el.classList.remove('open');
    }
}

// function setStatus(el, status) {
//   const parent = el.closest('.status-dropdown-resolve');
//   const statusBtn = parent.querySelector('.status');

//   // Reset classes
//   statusBtn.classList.remove('resolved', 'unresolved', 'open');

//   // Define colors
//   let color = '';
//   let cssClass = '';

//   if (status === 'Resolved') {
//     color = '#0a8754'; // Green (Publish color)
//     cssClass = 'resolved';
//   } else if (status === 'Unresolved') {
//     color = '#d4aa00'; // Yellow (Pending color)
//     cssClass = 'unpublished';
//   }

//   // Apply class & color
//   statusBtn.classList.add(cssClass);
//   statusBtn.style.color = color;

//   // Update inner text + arrow
//   statusBtn.innerHTML = `${status}
//     <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
//       viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2"
//       stroke-linecap="round" stroke-linejoin="round">
//       <polyline points="6 9 12 15 18 9"></polyline>
//     </svg>`;

//   // Close dropdown
//   parent.querySelector('.dropdown-menu').style.display = 'none';
// }