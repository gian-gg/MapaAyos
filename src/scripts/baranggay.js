function displayModal(report) {
  document.getElementById(
    "modalLabel"
  ).innerHTML = `<h5 class="modal-title" id="modalLabel">${report.title}</h5>`;

  document.getElementById("modal-body").innerHTML = `
            <img src="/MapaAyos/public/uploads/reports/${report.imagePath}" style="width: 100%;" alt="Report Image" />
            <p><strong>Description:</strong> ${report.description}</p>
            <p><strong>Status:</strong> ${report.status}</p>
            <p><strong>Created At:</strong> ${report.createdAt}</p>
        `;

  const bootstrapModal = new bootstrap.Modal(document.getElementById("modal"));
  bootstrapModal.show();
}
