function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const toggleIcon = document.getElementById("toggleIcon1");
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.remove("bi-eye-slash-fill");
    toggleIcon.classList.add("bi-eye-fill");
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.remove("bi-eye-fill");
    toggleIcon.classList.add("bi-eye-slash-fill");
  }
}

function toggleConfirmPasswordVisibility() {
  const confirmPasswordInput = document.getElementById("confirmPassword");
  const toggleIcon = document.getElementById("toggleIcon2");
  if (confirmPasswordInput.type === "password") {
    confirmPasswordInput.type = "text";
    toggleIcon.classList.remove("bi-eye-slash-fill");
    toggleIcon.classList.add("bi-eye-fill");
  } else {
    confirmPasswordInput.type = "password";
    toggleIcon.classList.remove("bi-eye-fill");
    toggleIcon.classList.add("bi-eye-slash-fill");
  }
}
