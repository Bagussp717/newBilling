document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const passwordIcon = document.getElementById("password-icon");
    const passwordToggle = document.getElementById("password-toggle");

    passwordToggle.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.classList.remove("ti-eye-off");
            passwordIcon.classList.add("ti-eye");
        } else {
            passwordInput.type = "password";
            passwordIcon.classList.remove("ti-eye");
            passwordIcon.classList.add("ti-eye-off");
        }
    });
});
