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

document.getElementById("showPermission").addEventListener("click", function () {
  document.getElementById("permissionSection").style.display = "block";
});


document.querySelectorAll(".tabs-wrapper").forEach(wrapper => {
  const tabsNav = wrapper.querySelector(".tabs-nav");
  const leftBtn = wrapper.querySelector(".tab-control.left");
  const rightBtn = wrapper.querySelector(".tab-control.right");

  leftBtn.addEventListener("click", () => {
    tabsNav.scrollBy({ left: -150, behavior: "smooth" });
  });

  rightBtn.addEventListener("click", () => {
    tabsNav.scrollBy({ left: 150, behavior: "smooth" });
  });
});



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
  backdrop.addEventListener('click', closeCheckModal);
  closeBt.addEventListener('click', closeCheckModal);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeCheckModal();
  });
})();

const actions = document.querySelector('.toolbar-actions');
const anyChecked = () => [...document.querySelectorAll('.row-check')].some(c => c.checked);
const toggleActions = () => actions.hidden = !anyChecked();

document.addEventListener('change', e => {
  if (e.target.classList.contains('row-check')) toggleActions();
});

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
// 

// service


const profileBtn = document.getElementById("profileBtn");
const profilePopup = document.getElementById("profilePopup");
const docModal = document.getElementById("docModal");

// Toggle profile popup or doc modal
profileBtn.addEventListener("click", function (e) {
  e.stopPropagation(); // Prevent document click from immediately closing
  // Toggle the desired popup:
  profilePopup.style.display = "none"; // hide other popups if needed
  docModal.style.display = docModal.style.display === "block" ? "none" : "block";
});

// Close any popup when clicking outside
document.addEventListener("click", function (e) {
  if (!e.target.closest("#profileBtn") && !e.target.closest(".popup")) {
    profilePopup.style.display = "none";
    docModal.style.display = "none";
  }
});

// Close modal when clicking the close button
document.querySelectorAll(".popup-close").forEach(btn => {
  btn.addEventListener("click", function () {
    const targetId = this.getAttribute("data-close");
    document.getElementById(targetId).style.display = "none";
  });
});





const modal = document.getElementById("service-details-modal");
const openBtn = document.getElementById("openServiceDetails");
const closeBtn = document.getElementById("closeServiceDetails");

openBtn.onclick = () => {
  modal.style.display = "flex";
}
closeBtn.onclick = () => {
  modal.style.display = "none";
}
window.onclick = (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
}

// user-provider-details

// Handle tab clicks
document.querySelectorAll(".tab").forEach(tab => {
  tab.addEventListener("click", () => {
    // remove active from all tabs & contents
    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
    document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));

    // activate clicked tab and content
    tab.classList.add("active");
    document.getElementById(tab.dataset.target).classList.add("active");
  });
});
//  active container
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


const statusBtn = document.querySelector(".status-btn");
const statusMenu = document.querySelector(".status-menu");
const statusOptions = document.querySelectorAll(".status-option");

// Default inactive
statusBtn.classList.add("active");

statusBtn.addEventListener("click", () => {
  statusMenu.style.display = statusMenu.style.display === "block" ? "none" : "block";
});

// Change status on option click
statusOptions.forEach(option => {
  option.addEventListener("click", () => {
    let status = option.textContent.trim();

    statusBtn.textContent = status + " â–¼";

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
  mobileToggle.innerHTML = 'â˜°';
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

// Close modals on outside click
window.onclick = (e) => {
  if (e.target.classList.contains("modal")) {
    e.target.style.display = "none";
  }
};

function toggleDropdown(trigger) {
  const menu = trigger.nextElementSibling;
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function toggleDropdown(trigger) {
  const menu = trigger.nextElementSibling;
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function setStatus(option, status) {
  const dropdown = option.closest(".status-dropdown");
  const statusEl = dropdown.querySelector(".status");

  // mapping
  const statusClassMap = {
    resolved: "resolved",
    unresolved: "inactive"
  };

  // remove all possible status classes
  statusEl.classList.remove(...Object.values(statusClassMap));

  // update text
  statusEl.textContent = status;

  // update class
  const className = statusClassMap[status.toLowerCase()];
  if (className) {
    statusEl.classList.add(className);
  }

  // hide menu
  dropdown.querySelector(".dropdown-menu").style.display = "none";
}



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



document.addEventListener("DOMContentLoaded", () => {
  const dropdown = document.querySelector(".custom-dropdown");
  const selected = dropdown.querySelector(".dropdown-selected");
  const options = dropdown.querySelector(".dropdown-options");
  const items = dropdown.querySelectorAll(".dropdown-options li");

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

  // close dropdown on outside click
  document.addEventListener("click", (e) => {
    if (!dropdown.contains(e.target)) {
      options.style.display = "none";
    }
  });
});




// user catogrey js
function openModal(modalId) {
  document.getElementById(modalId).style.display = 'flex';
}
function closeModal(modalId) {
  document.getElementById(modalId).style.display = 'none';
}

// // Position and show the delete popover
// function openDeletePopover(button) {
//     const popover = document.getElementById('deletePopover');
//     const rect = button.getBoundingClientRect();
//     popover.style.display = 'block';
//     popover.style.top = (rect.bottom + window.scrollY + 5) + 'px';
//     popover.style.left = (rect.left + window.scrollX - 20) + 'px';
// }

// // Close the delete popover
// function closeDeletePopover() {
//     document.getElementById('deletePopover').style.display = 'none';
// }

// // Hide popover if clicked outside
// window.addEventListener('click', function (event) {
//     const popover = document.getElementById('deletePopover');
//     if (popover.style.display === 'block' && !popover.contains(event.target) && !event.target.closest('.delete-btn')) {
//         closeDeletePopover();
//     }
// });








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
// end of user profile js






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
  mobileToggle.innerHTML = 'â˜°';
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




// user-profile
// Reset Password Modal
//




//user profile

