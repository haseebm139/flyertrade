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

// Tab functionality
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".tab").forEach(tab => {
        tab.addEventListener("click", function () {
            let targetId = tab.getAttribute("data-target");
            let wrapper = tab.closest(".tabs-section")?.parentElement || document;

            wrapper.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
            wrapper.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));

            tab.classList.add("active");

            let targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add("active");
            }
        });
    });

    document.querySelectorAll(".tab.active").forEach(activeTab => {
        let targetId = activeTab.getAttribute("data-target");
        let targetContent = document.getElementById(targetId);
        if (targetContent) {
            targetContent.classList.add("active");
        }
    });
});

// Permission section toggle
document.getElementById("showPermission").addEventListener("click", function () {
    document.getElementById("permissionSection").style.display = "block";
});

// Tab navigation controls
document.querySelectorAll(".tabs-wrapper").forEach(wrapper => {
    const tabsNav = wrapper.querySelector(".tabs-nav");
    const leftBtn = wrapper.querySelector(".tab-control.left");
    const rightBtn = wrapper.querySelector(".tab-control.right");

    if (leftBtn && tabsNav) {
        leftBtn.addEventListener("click", () => {
            tabsNav.scrollBy({ left: -150, behavior: "smooth" });
        });
    }

    if (rightBtn && tabsNav) {
        rightBtn.addEventListener("click", () => {
            tabsNav.scrollBy({ left: 150, behavior: "smooth" });
        });
    }
});

// Check modal functionality


// Toolbar actions

// Service details modal

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
// function openDeletePopover(button) {
//     const popover = document.getElementById('deletePopover');
//     if (!popover) return;

//     const rect = button.getBoundingClientRect();
//     popover.style.top = `${rect.top + window.scrollY + 30}px`;
//     popover.style.left = `${rect.left + window.scrollX - 50}px`;
//     popover.style.display = 'block';
// }

// function closeDeletePopover() {
//     const popover = document.getElementById('deletePopover');
//     if (popover) {
//         popover.style.display = 'none';
//     }
// }

// Hide popover if clicked outside
// window.addEventListener('click', function (event) {
//     const popover = document.getElementById('deletePopover');
//     if (popover && popover.style.display === 'block' && !popover.contains(event.target) && !event.target.closest('.delete-btn')) {
//         closeDeletePopover();
//     }
// });

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

