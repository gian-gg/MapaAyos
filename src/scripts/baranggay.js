function displayModal(report) {
  document.getElementById(
    "modalLabel"
  ).innerHTML = `<h5 class="modal-title fw-bold" id="modalLabel">${report.title}</h5>`;

  document.getElementById("modal-body").innerHTML = `
    <div class="report-modal">
      <div class="image-container mb-4">
        <img src="/MapaAyos/public/uploads/reports/${report.imagePath}" 
             class="img-fluid rounded shadow" 
             alt="Report Image" />
      </div>
      
      <div class="report-details">
        <div class="mb-3">
          <h6 class="text-muted mb-2">Description</h6>
          <p class="description">${report.description}</p>
        </div>
        
        <div class="mb-3">
          <h6 class="text-muted mb-2">Status</h6>
          <span class="badge ${getStatusBadgeClass(report.status)}">${
    report.status
  }</span>
        </div>
        
        <div>
          <h6 class="text-muted mb-2">Created</h6>
          <p class="text-secondary">
            <i class="bi bi-calendar3"></i> 
            ${formatDate(report.createdAt)}
          </p>
        </div>

        <div>
          <h6 class="text-muted mb-2">Moderator Comment:</h6>
          <p class="text-secondary">
            ${report.comment}
          </p>
        </div>
      </div>
    </div>
  `;

  // Add custom styles
  const style = document.createElement("style");
  style.textContent = `
    .report-modal .image-container {
      position: relative;
      overflow: hidden;
      border-radius: 8px;
    }
    
    .report-modal .description {
      font-size: 1rem;
      line-height: 1.6;
    }
    
    .report-modal .badge {
      font-size: 0.9rem;
      padding: 8px 12px;
    }
  `;
  document.head.appendChild(style);

  const bootstrapModal = new bootstrap.Modal(document.getElementById("modal"));
  bootstrapModal.show();
}

// Helper function to get appropriate badge class based on status
function getStatusBadgeClass(status) {
  const statusMap = {
    Pending: "bg-warning",
    "In Progress": "bg-info",
    Completed: "bg-success",
    Rejected: "bg-danger",
  };
  return statusMap[status] || "bg-secondary";
}

// Helper function to format date
function formatDate(dateString) {
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return new Date(dateString).toLocaleDateString("en-US", options);
}
