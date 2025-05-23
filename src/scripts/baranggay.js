import { formatDate } from "./utils/helpers.js";

function getStatusIcon(status) {
  const icons = {
    pending: '<i class="bi bi-clock"></i>',
    active: '<i class="bi bi-check2-circle"></i>',
    resolved: '<i class="bi bi-check2-all"></i>',
    denied: '<i class="bi bi-x-circle"></i>',
  };
  return icons[status.toLowerCase()] || "";
}

function displayReportModal(report) {
  document.getElementById(
    "reportModalLabel"
  ).innerHTML = `<h5 class="modal-title" id="modalLabel">${report.title}</h5>`;

  document.getElementById("reportModal-body").innerHTML = `
    <div class="report-modal">
      <div class="image-container">
        <img src="/MapaAyos/public/uploads/reports/${report.imagePath}" 
             alt="Report Image" />
      </div>
      
      <div class="report-details">
        <div class="section">
          <h6 class="section-title">Description</h6>
          <p class="description">${report.description}</p>
        </div>
        
        <div class="section">
          <h6 class="section-title">Status</h6>
          <span class="badge ${report.status.toLowerCase()}">
            ${getStatusIcon(report.status)}
            ${report.status}
          </span>
        </div>
        
        <div class="section">
          <h6 class="section-title">Report Details</h6>
          <div class="meta-info">
            <i class="bi bi-calendar3"></i>
            <span>Created ${formatDate(report.createdAt)}</span>
          </div>
        </div>

        ${
          report.comment
            ? `
          <div class="section">
            <h6 class="section-title">Moderator Comment</h6>
            <p class="comment">${report.comment}</p>
          </div>
        `
            : ""
        }
      </div>
    </div>
  `;

  const bootstrapModal = new bootstrap.Modal(
    document.getElementById("reportModal")
  );
  bootstrapModal.show();
}

function displayEditModal(data) {
  document.getElementById("edit-description").value = data.description;
  document.getElementById("edit-population").value = data.population;
  document.getElementById("edit-landArea").value = data.landArea;
  document.getElementById("edit-phone").value = data.phone;
  document.getElementById("edit-email").value = data.email;
  document.getElementById("edit-address").value = data.address;
  document.getElementById("edit-weekdayHours").value =
    data.operating_hours_weekdays;
  document.getElementById("edit-saturdayHours").value =
    data.operating_hours_saturday;

  const bootstrapModal = new bootstrap.Modal(
    document.getElementById("editModal")
  );
  bootstrapModal.show();
}

window.displayEditModal = displayEditModal;
window.displayReportModal = displayReportModal;
