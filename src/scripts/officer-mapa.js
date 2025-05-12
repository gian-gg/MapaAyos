import {
  isInBaranggay,
  createBaranggayBoundary,
  displayPopUp,
  removeAllPins,
} from "./utils/mapa-utils.js";

import { capitalizeFirstLetter } from "./utils/helpers.js";
import { fetchAPI } from "./utils/api-utils.js";
import { showToast } from "./utils/toast-utils.js";

let currentBaranggayCoords = null;
let currentBaranggayPolygon = null;

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
  fetchAPI(
    "http://localhost/MapaAyos/api/reports?mode=getReportByID&reportID=" +
      reportID
  ).then((data) => {
    const report = data.report;

    if (!report) return;

    removeAllPins();

    document.querySelectorAll(".report-row").forEach((row) => {
      row.classList.remove("active");
    });
    document.getElementById("report-" + reportID).classList.add("active");

    let reportElement = `
      <img src="/MapaAyos/public/uploads/reports/${
        report["imagePath"]
      }" alt="Report Image" />
      <h3>${capitalizeFirstLetter(report["title"])}</h3>
      <p>${capitalizeFirstLetter(report["description"])}</p>

      <form method="POST" id="verify-report-form">
        <input type="hidden" name="reportID" value="${reportID}" />
        <label for="verification-status">Verification Status:</label>
        <select id="verification-status" name="verificationStatus" required>
          <option value="null" selected disabled>Select</option>
      `;

    if (report["status"] === "pending") {
      reportElement += `
              <option value="active">Active</option>
              <option value="denied">Deny</option>
          `;
    } else if (report["status"] === "active") {
      reportElement += `
              <option value="resolved">Resolve</option>
              <option value="denied">Deny</option>
          `;
    }
    if (report["status"] === "resolved") {
      reportElement += `
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="denied">Deny</option>
          `;
    } else if (report["status"] === "deny") {
      reportElement += `
              <option value="active">Active</option>
              <option value="pending">Pending</option>
          `;
    }

    reportElement += `
              </select>
        <label for="comment">Add Comment:</label>
        <textarea id="comment" name="comment" rows="4" placeholder="Write your comment here..." required></textarea>
        <button type="submit">Update Status</button>
      </form>
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
window.displayReport = displayReport;
