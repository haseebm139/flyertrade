
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");
const passwordError = document.getElementById("passwordError");
const form = document.getElementById("loginForm");

togglePassword.addEventListener("click", () => {
    const isPassword = passwordInput.getAttribute("type") === "password";
    passwordInput.setAttribute("type", isPassword ? "text" : "password");

    // Toggle icon classes
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
});

