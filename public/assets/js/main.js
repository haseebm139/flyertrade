document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("edit-btn");
    const saveBtn = document.getElementById("save-btn");
    const reviewText = document.getElementById("review-text");
    const editReview = document.getElementById("edit-review");
    const editDeleteBtns = document.getElementById("edit-delete-buttons");

    editBtn.addEventListener("click", function () {
        // Show textarea
        editReview.style.display = "block";
        reviewText.style.display = "none";

        // Hide edit + delete buttons
        editDeleteBtns.style.display = "none";

        // Show save button
        saveBtn.style.display = "inline-block";

        // Fill current text
        editReview.value = reviewText.innerText.trim();
    });

    saveBtn.addEventListener("click", function () {
        // Save updated text
        reviewText.innerText = editReview.value.trim();

        // Hide textarea, show review
        editReview.style.display = "none";
        reviewText.style.display = "block";

        // Show buttons again
        editDeleteBtns.style.display = "flex";
        saveBtn.style.display = "none";
    });
});


// Select all functionality
const selectAll = document.getElementById("selectAll");
const userCheckboxes = document.querySelectorAll(".select-user");

selectAll.addEventListener("change", function () {
    userCheckboxes.forEach(cb => cb.checked = this.checked);
});

userCheckboxes.forEach(cb => {
    cb.addEventListener("change", () => {
        const allChecked = [...userCheckboxes].every(c => c.checked);
        selectAll.checked = allChecked;
    });
});

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
    const tabButtons = document.querySelectorAll(".filter-btn");

    // ✅ Select ALL buttons with class .new-email-btn (handles both)
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

    // 🟢 Open Compose Email on any new-email-btn click
    newEmailBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            show(emailCompose);
        });
    });

    // 🟢 Open Chat on new message button
    if (newMsgBtn) {
        newMsgBtn.addEventListener("click", () => {
            show(messageChat);
        });
    }

    // 🟢 When user clicks an email list item → open email view
    document.querySelectorAll("#emails .user-list-item").forEach(item => {
        item.addEventListener("click", () => {
            show(viewEmail);
        });
    });

    // 🟢 When user clicks a chat list item → open chat
    document.querySelectorAll("#chats .user-list-item").forEach(item => {
        item.addEventListener("click", () => {
            show(messageChat);
        });
    });

    // 🟢 Filter buttons active state + show content panel
    tabButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            tabButtons.forEach(b => b.classList.remove("tab-active"));
            btn.classList.add("tab-active");
            show(contentPanel);
        });
    });
});
