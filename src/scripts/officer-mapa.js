import { createBaranggayBoundary, removeAllPins } from "./utils/mapa-utils.js";

import { capitalizeFirstLetter } from "./utils/helpers.js";
import { fetchAPI } from "./utils/api-utils.js";

let currentBaranggayCoords = null;
let currentBaranggayPolygon = null;
let currentReportID = null;

fetchAPI(
  "http://localhost/MapaAyos/api/baranggay?mode=getBaranggayByName&baranggay=" +
    currentBaranggay
).then((data) => {
  currentBaranggayCoords = JSON.parse(data.data[0].geojson)["coordinates"];

  currentBaranggayPolygon = createBaranggayBoundary(currentBaranggayPolygon, [
    currentBaranggayCoords,
  ]);
});

function displayReport(reportID) {
  if (currentReportID === reportID) return;

  currentReportID = reportID;

  document.querySelectorAll(".report-row").forEach((row) => {
    row.classList.remove("active");
  });
  document.getElementById("report-" + reportID).classList.add("active");

  document.getElementById("report-information").innerHTML = `
    <div class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-3 text-muted">Loading report details...</p>
    </div>
  `;

  fetchAPI(
    "http://localhost/MapaAyos/api/reports?mode=getReportByID&reportID=" +
      reportID
  ).then((data) => {
    const report = data.report;

    if (!report) {
      document.getElementById("report-information").innerHTML = `
        <div class="text-center py-5 text-muted">
          <i class="bi bi-exclamation-circle display-4"></i>
          <p class="mt-3">Report not found</p>
        </div>
      `;
      return;
    }

    removeAllPins();

    let statusClass = "";
    let statusIcon = "";

    switch (report.status) {
      case "pending":
        statusClass = "text-warning";
        statusIcon = "hourglass-split";
        break;
      case "resolved":
        statusClass = "text-success";
        statusIcon = "check-circle";
        break;
      case "denied":
        statusClass = "text-danger";
        statusIcon = "x-circle";
        break;
      default:
        statusClass = "text-primary";
        statusIcon = "circle";
    }

    let reportElement = `
      <div class="position-relative">
        <img src="/MapaAyos/public/uploads/reports/${
          report["imagePath"]
        }" alt="Report Image" class="report-image" />
        <div class="position-absolute bottom-0 start-0 p-2 m-2 bg-white rounded-pill ${statusClass}">
          <i class="bi bi-${statusIcon} me-2"></i>
          ${capitalizeFirstLetter(report["status"])}
        </div>
      </div>
      <div class="p-3">
        <h3 class="mb-3">${capitalizeFirstLetter(report["title"])}</h3>
        <p class="text-secondary mb-4">${capitalizeFirstLetter(
          report["description"]
        )}</p>

        <form method="POST" id="verify-report-form" class="mt-4">
          <input type="hidden" name="reportID" value="${reportID}" />
          
          <div class="mb-3">
            <label for="verification-status" class="form-label">
              <i class="bi bi-check-square me-2"></i>
              Update Status
            </label>
            <select id="verification-status" name="verificationStatus" class="form-select" required>
              <option value="null" selected disabled>Select new status</option>
    `;

    if (report["status"] === "pending") {
      reportElement += `
              <option value="active">Mark as Active</option>
              <option value="denied">Deny Report</option>
          `;
    } else if (report["status"] === "active") {
      reportElement += `
              <option value="resolved">Mark as Resolved</option>
              <option value="denied">Deny Report</option>
          `;
    }
    if (report["status"] === "resolved") {
      reportElement += `
              <option value="active">Reactivate Report</option>
              <option value="pending">Mark as Pending</option>
              <option value="denied">Deny Report</option>
          `;
    } else if (report["status"] === "denied") {
      reportElement += `
              <option value="active">Reactivate Report</option>
              <option value="pending">Mark as Pending</option>
          `;
    }

    reportElement += `
            </select>
          </div>

          <div class="mb-3">
            <label for="comment" class="form-label">
              <i class="bi bi-chat-left-text me-2"></i>
              Add Comment
            </label>
            <textarea 
              id="comment" 
              name="comment" 
              rows="4" 
              class="form-control" 
              placeholder="Provide details about your decision..."
              required
            ></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check2 me-2"></i>
            Update Report Status
          </button>
        </form>
      </div>
    `;

    document.getElementById("report-information").innerHTML = reportElement;

    const marker = L.marker([report.lat, report.lng]).addTo(map);
    marker.setIcon(
      L.icon({
        iconUrl: "/MapaAyos/public/img/pins/default.png",
        iconSize: [24, 32],
        iconAnchor: [12, 12],
      })
    );

    map.flyTo(marker.getLatLng(), 15, { duration: 1 });
  });
}

document.addEventListener("submit", function (e) {
  if (e.target.id === "verify-report-form") {
    e.preventDefault();

    const formData = new FormData(e.target);
    const submitButton = e.target.querySelector('button[type="submit"]');

    submitButton.disabled = true;
    submitButton.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
      Updating Status...
    `;

    fetch(window.location.href, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (response.ok) {
          window.location.reload();
        } else {
          throw new Error("Failed to update report");
        }
      })
      .catch((error) => {
        submitButton.disabled = false;
        submitButton.innerHTML = `
        <i class="bi bi-check2 me-2"></i>
        Update Report Status
      `;
        alert("Failed to update report status. Please try again.");
      });
  }
});

window.displayReport = displayReport;
