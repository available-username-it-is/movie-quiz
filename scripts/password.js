let passwordToggleElement = document.getElementById("password-toggler");
let passwordField = document.getElementById("password");
let confirmPasswordToggleElement = document.getElementById("confirm-password-toggler");
let confirmPasswordField = document.getElementById("confirm-password");


passwordToggleElement.addEventListener('click', () => {
    if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordToggleElement.innerHTML = "visibility_off";
    } else {
        passwordField.type = "password";
        passwordToggleElement.innerHTML = "visibility";
    }
})

confirmPasswordToggleElement.addEventListener('click', () => {
    if (confirmPasswordField.type === "password") {
        confirmPasswordField.type = "text";
        confirmPasswordToggleElement.innerHTML = "visibility_off";
    } else {
        confirmPasswordField.type = "password";
        confirmPasswordToggleElement.innerHTML = "visibility";
    }
})