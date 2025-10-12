// Header-specific functionality fix
document.addEventListener('DOMContentLoaded', function () {
    console.log('Header fix script loaded');

    // Wait a bit to ensure main.js has loaded
    setTimeout(() => {
        initializeHeaderFix();
    }, 200);
});

// Utility function to close all popups
function closeAllPopups() {
    const profilePopup = document.getElementById("profilePopup");
    const notifPopup = document.getElementById("notifPopup");

    if (profilePopup) profilePopup.style.display = "none";
    if (notifPopup) notifPopup.style.display = "none";

    console.log('All popups closed');
}

function initializeHeaderFix() {
    console.log('Initializing header fix...');

    // Profile button functionality
    const profileBtn = document.getElementById("profileBtn");
    const profilePopup = document.getElementById("profilePopup");

    console.log('Profile button found:', !!profileBtn);
    console.log('Profile popup found:', !!profilePopup);

    if (profileBtn && profilePopup) {
        // Add event listener to profile button
        profileBtn.addEventListener("click", function (e) {
            console.log('Profile button clicked!');
            e.preventDefault();
            e.stopPropagation();

            // Close notification popup if open
            const notifPopup = document.getElementById("notifPopup");
            if (notifPopup) {
                notifPopup.style.display = "none";
                console.log('Notification popup closed by profile button');
            }

            const isVisible = profilePopup.style.display === "block" ||
                (profilePopup.style.display === "" &&
                    window.getComputedStyle(profilePopup).display !== "none");

            if (isVisible) {
                profilePopup.style.display = "none";
                console.log('Profile popup hidden');
            } else {
                profilePopup.style.display = "block";
                console.log('Profile popup shown');
            }
        });

        console.log('Profile button functionality initialized');
    } else {
        console.log('Profile button or popup not found');
    }

    // Notification button functionality
    const notifBtn = document.getElementById("notifBtn");
    const notifPopup = document.getElementById("notifPopup");

    console.log('Notification button found:', !!notifBtn);
    console.log('Notification popup found:', !!notifPopup);

    if (notifBtn && notifPopup) {
        notifBtn.addEventListener("click", function (e) {
            console.log('Notification button clicked!');
            e.preventDefault();
            e.stopPropagation();

            // Close profile popup if open
            const profilePopup = document.getElementById("profilePopup");
            if (profilePopup) {
                profilePopup.style.display = "none";
                console.log('Profile popup closed by notification button');
            }

            const isVisible = notifPopup.style.display === "block" ||
                (notifPopup.style.display === "" &&
                    window.getComputedStyle(notifPopup).display !== "none");

            if (isVisible) {
                notifPopup.style.display = "none";
                console.log('Notification popup hidden');
            } else {
                notifPopup.style.display = "block";
                console.log('Notification popup shown');
            }
        });

        console.log('Notification button functionality initialized');
    } else {
        console.log('Notification button or popup not found');
    }

    // Enhanced close functionality
    document.querySelectorAll(".popup-close").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const targetId = this.getAttribute("data-close");
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.style.display = "none";
                console.log(`Closed popup: ${targetId}`);
            }
        });
    });
}
