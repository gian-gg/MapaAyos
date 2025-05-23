function showToast(title, description) {
  document.getElementById("ma-toast-title").innerText = title;
  document.getElementById("ma-toast-body").innerText = description;
  new bootstrap.Toast(document.getElementById("ma-toast")).show();
}

export { showToast };
