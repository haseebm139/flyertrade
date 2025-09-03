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
        // If you load data via AJAX, use 'ajax' option here and remove static tbody
    });
});



// pop ups


document.getElementById("profileBtn").addEventListener("click", function (e) {
    document.getElementById("profilePopup").style.display =
        document.getElementById("profilePopup").style.display === "block" ? "none" : "block";
    document.getElementById("notifPopup").style.display = "none";
});

// Close popups on outside click
document.addEventListener("click", function (e) {
    if (!e.target.closest("#notifBtn") && !e.target.closest("#profileBtn")) {
        document.getElementById("notifPopup").style.display = "none";
        document.getElementById("profilePopup").style.display = "none";
    }
});




// Notification popup toggle
document.getElementById("notifBtn").addEventListener("click", function (e) {
    const notifPopup = document.getElementById("notifPopup");
    const providerModal = document.getElementById("providerModal");

    notifPopup.style.display =
        notifPopup.style.display === "block" ? "none" : "block";

    // Close provider modal if open
    providerModal.style.display = "none";

    e.stopPropagation(); // Prevent outside click closing immediately
});

// Open provider popup from notification
document.querySelectorAll(".notification-view").forEach((btn) => {
    btn.addEventListener("click", function (e) {
        const modalId = this.getAttribute("data-modal");
        if (modalId) {
            document.getElementById(modalId).style.display = "block";
            // Close notification popup
            document.getElementById("notifPopup").style.display = "none";
        }
        e.stopPropagation();
    });
});

// Close both popups on outside click
document.addEventListener("click", function (e) {
    if (!e.target.closest(".popup") && !e.target.closest(".provider-modal") && !e.target.closest("#notifBtn")) {
        document.getElementById("notifPopup").style.display = "none";
        document.getElementById("providerModal").style.display = "none";
    }
});

// Close buttons
document.querySelectorAll(".popup-close, .provider-modal-close, .provider-back-icon").forEach((btn) => {
    btn.addEventListener("click", function () {
        const popup = this.closest(".popup, .provider-modal");
        if (popup) popup.style.display = "none";
    });
});



// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function () {
    const mobileToggle = document.createElement('button');
    mobileToggle.className = 'mobile-menu-toggle d-lg-none';
    mobileToggle.innerHTML = '☰';
    document.body.prepend(mobileToggle);

    mobileToggle.addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 360 &&
            !e.target.closest('.sidebar') &&
            !e.target.closest('.mobile-menu-toggle')) {
            document.querySelector('.sidebar').classList.remove('active');
        }
    });
});



// add user

// Add User Modal
document.getElementById("openAddUserModal").onclick = () => {
    document.getElementById("addUserModal").style.display = "flex";
};
document.getElementById("closeAddUserModal").onclick = () => {
    document.getElementById("addUserModal").style.display = "none";
};
document.querySelector(".cancel-btn").onclick = () => {
    document.getElementById("addUserModal").style.display = "none";
};

// Filter Modal
document.getElementById("openFilterModal").onclick = () => {
    document.getElementById("filterModal").style.display = "flex";
};
document.getElementById("closeFilterModal").onclick = () => {
    document.getElementById("filterModal").style.display = "none";
};
document.querySelector(".reset-btn").onclick = () => {
    document.getElementById("filterModal").style.display = "none";
};

// Close modals on outside click
window.onclick = (e) => {
    if (e.target.classList.contains("modal")) {
        e.target.style.display = "none";
    }
};


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




// sort

document.querySelectorAll(".users-table th.sortable").forEach(th => {
    th.addEventListener("click", () => {
        const table = th.closest("table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const columnIndex = th.dataset.column;
        const currentIcon = th.querySelector(".sort-icon");

        // reset all icons


        // toggle sort order
        let asc = th.classList.toggle("asc");
        th.classList.remove("desc");
        if (!asc) {
            th.classList.add("desc");
        }



        // sort rows
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

        // re-append sorted rows
        tbody.innerHTML = "";
        sortedRows.forEach(row => tbody.appendChild(row));
    });
});





// user catogrey js
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Position and show the delete popover
function openDeletePopover(button) {
    const popover = document.getElementById('deletePopover');
    const rect = button.getBoundingClientRect();
    popover.style.display = 'block';
    popover.style.top = (rect.bottom + window.scrollY + 5) + 'px';
    popover.style.left = (rect.left + window.scrollX - 20) + 'px';
}

// Close the delete popover
function closeDeletePopover() {
    document.getElementById('deletePopover').style.display = 'none';
}

// Hide popover if clicked outside
window.addEventListener('click', function (event) {
    const popover = document.getElementById('deletePopover');
    if (popover.style.display === 'block' && !popover.contains(event.target) && !event.target.closest('.delete-btn')) {
        closeDeletePopover();
    }
});




function openUserModal(name, email, image) {
    // Main user info
    document.getElementById('userName').textContent = name;
    document.getElementById('userEmail').textContent = email;
    document.getElementById('userImage').src = image;

    // Populate user list
    const userList = document.getElementById('userList');
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

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}




function openDeletePopover(button) {
    const popover = document.getElementById('deletePopover');
    const rect = button.getBoundingClientRect();

    // Position the popover near the delete button
    popover.style.top = `${rect.top + window.scrollY + 30}px`;
    popover.style.left = `${rect.left + window.scrollX - 50}px`;

    popover.style.display = 'block';
}

function closeDeletePopover() {
    document.getElementById('deletePopover').style.display = 'none';
}

// booking

function openBookingModal() {
    document.getElementById('view-booking').style.display = 'flex';
}

function closeBookingModal() {
    document.getElementById('view-booking').style.display = 'none';
}

// Close modal on outside click
window.onclick = function (event) {
    const modal = document.getElementById('view-booking');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}


document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('initiateModal');
    const modalMessage = document.getElementById('modalMessage');
    const closeBtn = modal.querySelector('.close-btn');
    const cancelBtn = modal.querySelector('.cancel-btn');

    // Get all trigger buttons
    const triggerButtons = document.querySelectorAll('.initiateBtn');

    triggerButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const userName = button.getAttribute('data-user') || 'this user';
            modalMessage.textContent = `Are you sure you want to initiate payout to ${userName}?`;
            modal.style.display = 'flex';
        });
    });

    // Close modal (X or Cancel)
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    cancelBtn.addEventListener('click', () => modal.style.display = 'none');

    // Close modal when clicking outside content
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
