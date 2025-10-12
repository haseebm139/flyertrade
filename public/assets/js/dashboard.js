// Dashboard-specific JavaScript functionality
$(document).ready(function () {
    $('#recentUsersTable').DataTable({
        pageLength: 6,
        lengthChange: false,
        ordering: true,
        responsive: true,
        columnDefs: [{
            orderable: true,
            targets: [0, 1, 2, 3]
        }],
    });
});

// Modal functionality
document.addEventListener("DOMContentLoaded", () => {
    const addUserModal = document.getElementById("addUserModal");
    const openAddUserBtn = document.getElementById("openAddUserModal");
    const closeAddUserBtn = document.getElementById("closeAddUserModal");
    const cancelBtn = document.querySelector(".cancel-btn");

    if (openAddUserBtn && addUserModal) {
        openAddUserBtn.onclick = () => {
            addUserModal.style.display = "flex";
        };
    }

    if (closeAddUserBtn && addUserModal) {
        closeAddUserBtn.onclick = () => {
            addUserModal.style.display = "none";
        };
    }

    if (cancelBtn && addUserModal) {
        cancelBtn.onclick = () => {
            addUserModal.style.display = "none";
        };
    }
});

// Role modal functionality
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addRoleModal");
    const openBtn = document.getElementById("openaddRoleModal");
    const closeBtn = document.getElementById("closeaddRoleModal");

    if (openBtn && modal) {
        openBtn.addEventListener("click", function () {
            modal.style.display = "flex";
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    // Close modal if clicked outside the modal-content
    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});

// Tab functionality (scoped per tabs-wrapper/section)
document.addEventListener("DOMContentLoaded", function () {
    function getContainerFromTab(tab) {
        const wrapper = tab.closest(".tabs-wrapper");
        // Container is the element that contains both the wrapper and the contents
        return wrapper ? (wrapper.parentElement || document) : (tab.closest(".permission-section") || document);
    }

    // Click handling
    document.querySelectorAll(".tab").forEach(tab => {
        tab.addEventListener("click", function () {
            const container = getContainerFromTab(tab);
            const targetId = tab.getAttribute("data-target");
            if (!targetId) return;

            // Prefer scoped lookup; fallback to global if needed
            const targetContent = (container.querySelector(`#${CSS.escape(targetId)}`)) || document.getElementById(targetId);
            if (!targetContent) return; // do nothing if no matching content exists

            // Deactivate within this container only
            container.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
            container.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));

            // Activate current tab and content
            tab.classList.add("active");
            targetContent.classList.add("active");
        });
    });

    // Initialize per wrapper so each group shows its active content
    document.querySelectorAll(".tabs-wrapper").forEach(wrapper => {
        const container = wrapper.parentElement || document;
        const activeTab = wrapper.querySelector(".tab.active") || wrapper.querySelector(".tab");
        if (!activeTab) return;
        const targetId = activeTab.getAttribute("data-target");
        if (!targetId) return;

        // Ensure only this group's contents are active
        container.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));
        activeTab.classList.add("active");
        const targetContent = (container.querySelector(`#${CSS.escape(targetId)}`)) || document.getElementById(targetId);
        if (targetContent) targetContent.classList.add("active");
    });
});

// Permission section toggle (guard if missing on page)
(() => {
    const showBtn = document.getElementById("showPermission");
    const section = document.getElementById("permissionSection");
    if (showBtn && section) {
        showBtn.addEventListener("click", function (e) {
            e.preventDefault();
            section.style.display = "block";
        });
    }
})();

// Tab navigation controls
document.querySelectorAll(".tabs-wrapper").forEach(wrapper => {
    const tabsNav = wrapper.querySelector(".tabs-nav");
    const leftBtn = wrapper.querySelector(".tab-control.left");
    const rightBtn = wrapper.querySelector(".tab-control.right");

    if (leftBtn && tabsNav) {
        leftBtn.addEventListener("click", (e) => {
            e.preventDefault();
            tabsNav.scrollBy({ left: -150, behavior: "smooth" });
        });
    }

    if (rightBtn && tabsNav) {
        rightBtn.addEventListener("click", (e) => {
            e.preventDefault();
            tabsNav.scrollBy({ left: 150, behavior: "smooth" });
        });
    }
});

// Check modal functionality
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

// Toolbar actions
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

// Service details modal
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

// Status dropdown functionality
const statusBtn = document.querySelector(".status-btn");
const statusMenu = document.querySelector(".status-menu");
const statusOptions = document.querySelectorAll(".status-option");

if (statusBtn && statusMenu) {
    // Default inactive
    statusBtn.classList.add("active");

    statusBtn.addEventListener("click", () => {
        statusMenu.style.display = statusMenu.style.display === "block" ? "none" : "block";
    });

    // Change status on option click
    statusOptions.forEach(option => {
        option.addEventListener("click", () => {
            let status = option.textContent.trim();

            statusBtn.textContent = status + " ▼";

            if (status === "Active") {
                statusBtn.classList.remove("inactive");
                statusBtn.classList.add("active");
            } else {
                statusBtn.classList.remove("active");
                statusBtn.classList.add("inactive");
            }

            statusMenu.style.display = "none";
        });
    });

    // Close dropdown if clicked outside
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".status-dropdown")) {
            statusMenu.style.display = "none";
        }
    });
}

// Filter modal functionality
const openFilterBtn = document.getElementById("openFilterModal");
const closeFilterBtn = document.getElementById("closeFilterModal");
const resetBtn = document.querySelector(".reset-btn");
const filterModal = document.getElementById("filterModal");

if (openFilterBtn && filterModal) {
    openFilterBtn.onclick = () => {
        filterModal.style.display = "flex";
    };
}

if (closeFilterBtn && filterModal) {
    closeFilterBtn.onclick = () => {
        filterModal.style.display = "none";
    };
}

if (resetBtn && filterModal) {
    resetBtn.onclick = () => {
        filterModal.style.display = "none";
    };
}

// Generic modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Actions dropdown functionality
document.querySelectorAll('.actions-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        let parent = this.parentElement;
        parent.classList.toggle('active');

        // Close other open menus
        document.querySelectorAll('.actions-dropdown').forEach(drop => {
            if (drop !== parent) drop.classList.remove('active');
        });
    });
});

// Close dropdown on outside click
document.addEventListener('click', () => {
    document.querySelectorAll('.actions-dropdown').forEach(drop => {
        drop.classList.remove('active');
    });
});

// Table sorting functionality
document.querySelectorAll(".users-table th.sortable").forEach(th => {
    th.addEventListener("click", () => {
        const table = th.closest("table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const columnIndex = th.dataset.column;

        // Toggle sort order
        let asc = th.classList.toggle("asc");
        th.classList.remove("desc");
        if (!asc) {
            th.classList.add("desc");
        }

        // Sort rows
        const sortedRows = rows.sort((a, b) => {
            let aText = a.cells[columnIndex].innerText.trim().toLowerCase();
            let bText = b.cells[columnIndex].innerText.trim().toLowerCase();

            if (!isNaN(aText) && !isNaN(bText)) {
                aText = Number(aText);
                bText = Number(bText);
            }

            if (asc) {
                return aText > bText ? 1 : -1;
            } else {
                return aText < bText ? 1 : -1;
            }
        });

        // Re-append sorted rows
        tbody.innerHTML = "";
        sortedRows.forEach(row => tbody.appendChild(row));
    });
});

// Custom dropdown functionality
document.addEventListener("DOMContentLoaded", () => {
    const dropdown = document.querySelector(".custom-dropdown");
    if (!dropdown) return;

    const selected = dropdown.querySelector(".dropdown-selected");
    const options = dropdown.querySelector(".dropdown-options");
    const items = dropdown.querySelectorAll(".dropdown-options li");

    if (selected && options) {
        selected.addEventListener("click", () => {
            options.style.display = options.style.display === "block" ? "none" : "block";
        });

        items.forEach(item => {
            item.addEventListener("click", () => {
                items.forEach(i => i.classList.remove("selected"));
                item.classList.add("selected");
                selected.textContent = item.textContent;
                options.style.display = "none";
            });
        });

        // Close dropdown on outside click
        document.addEventListener("click", (e) => {
            if (!dropdown.contains(e.target)) {
                options.style.display = "none";
            }
        });
    }
});

// Delete popover functionality
function openDeletePopover(button) {
    const popover = document.getElementById('deletePopover');
    if (!popover) return;

    const rect = button.getBoundingClientRect();
    popover.style.top = `${rect.top + window.scrollY + 30}px`;
    popover.style.left = `${rect.left + window.scrollX - 50}px`;
    popover.style.display = 'block';
}

function closeDeletePopover() {
    const popover = document.getElementById('deletePopover');
    if (popover) {
        popover.style.display = 'none';
    }
}

// Hide popover if clicked outside
window.addEventListener('click', function (event) {
    const popover = document.getElementById('deletePopover');
    if (popover && popover.style.display === 'block' && !popover.contains(event.target) && !event.target.closest('.delete-btn')) {
        closeDeletePopover();
    }
});

// Booking modal functionality
function openBookingModal() {
    const modal = document.getElementById('view-booking');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeBookingModal() {
    const modal = document.getElementById('view-booking');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function () {
    const mobileToggle = document.createElement('button');
    mobileToggle.className = 'mobile-menu-toggle d-lg-none';
    mobileToggle.innerHTML = '☰';
    document.body.prepend(mobileToggle);

    mobileToggle.addEventListener('click', function () {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 360 &&
            !e.target.closest('.sidebar') &&
            !e.target.closest('.mobile-menu-toggle')) {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.remove('active');
            }
        }
    });
});

// User modal functionality
function openUserModal(name, email, image) {
    // Main user info
    const userName = document.getElementById('userName');
    const userEmail = document.getElementById('userEmail');
    const userImage = document.getElementById('userImage');

    if (userName) userName.textContent = name;
    if (userEmail) userEmail.textContent = email;
    if (userImage) userImage.src = image;

    // Populate user list
    const userList = document.getElementById('userList');
    if (userList) {
        userList.innerHTML = '';
        for (let i = 0; i < 10; i++) {
            userList.innerHTML += `
            <div class="list-group-item d-flex align-items-center">
              <img src="${image}" class="rounded-circle me-3" style="width:35px; height:35px; object-fit:cover;" alt="User">
              <div>
                <h6 class="mb-0">${name}</h6>
                <small class="text-muted">${email}</small>
              </div>
            </div>`;
        }
    }

    // Show modal
    const modal = document.getElementById('userModal');
    if (modal && typeof bootstrap !== 'undefined') {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }
}

// Initiate payout modal
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('initiateModal');
    if (!modal) return;

    const modalMessage = document.getElementById('modalMessage');
    const closeBtn = modal.querySelector('.close-btn');
    const cancelBtn = modal.querySelector('.cancel-btn');

    // Get all trigger buttons
    const triggerButtons = document.querySelectorAll('.initiateBtn');

    triggerButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const userName = button.getAttribute('data-user') || 'this user';
            if (modalMessage) {
                modalMessage.textContent = `Are you sure you want to initiate payout to ${userName}?`;
            }
            modal.style.display = 'flex';
        });
    });

    // Close modal (X or Cancel)
    if (closeBtn) {
        closeBtn.addEventListener('click', () => modal.style.display = 'none');
    }
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => modal.style.display = 'none');
    }

    // Close modal when clicking outside content
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});

// Close modals on outside click
window.addEventListener('click', (e) => {
    if (e.target.classList.contains("modal")) {
        e.target.style.display = "none";
    }
});
