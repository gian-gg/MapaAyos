import {
  isInBaranggay,
  displayReports,
  createBaranggayBoundary,
  displayPopUp,
  removeAllPins,
} from "./utils/mapa-utils.js";

import { capitalizeFirstLetter } from "./utils/helpers.js";
import { fetchAPI } from "./utils/api-utils.js";
import { showToast } from "./utils/toast-utils.js";

let currentBaranggayCoords = null;
let currentBaranggayPolygon = null;

const infoContainer = document.getElementById("info-container");
const baranggaySelect = document.getElementById("selectBaranggayInput");
const selectFilterInput = document.getElementById("selectFilterInput");

// Event listener for map click
map.on("click", (e) => {
  if (currentUser === "") return;
  if (currentUserRole === "admin" || currentUserRole === "official") return;

  if (currentBaranggayCoords === null) {
    showToast("Error", "Please select a baranggay first before reporting.");
    return;
  }

  const { lat, lng } = e.latlng;

  if (isInBaranggay([lat, lng], currentBaranggayCoords)) {
    displayPopUp(lat, lng, baranggaySelect.value);
  } else {
    showToast("Error", "You clicked outside the baranggay boundary.");
  }
});

baranggaySelect.addEventListener("change", (e) => {
  const selectedBaranggay = e.target.value;

  infoContainer.classList.remove("hidden");

  fetchAPI(
    "http://localhost/MapaAyos/api/baranggay?mode=getBaranggayByName&baranggay=" +
      selectedBaranggay
  ).then((data) => {
    currentBaranggayCoords = JSON.parse(data.data[0].geojson)["coordinates"];

    infoContainer.innerHTML = `
    <img src="/MapaAyos/public/img/baranggays/${
      data.data[0].name
    }.jpg" class="card-img-top" alt="Report Image" />
    <div class="card-body text-center">
      <h5 class="card-title text-capitalize fw-semibold">${capitalizeFirstLetter(
        data.data[0].name
      )}</h5>
      <p class="card-text text-muted mb-0">
        ${capitalizeFirstLetter(data.data[0].city)}, ${capitalizeFirstLetter(
      data.data[0].country
    )}
      </p>
      <a href="/MapaAyos/baranggays?baranggay=${
        data.data[0].name
      }" class="btn btn-primary mt-2 primary-color" id="reportBtn">
        Go to Baranggays Page
      </a>
    </div>
`;

    currentBaranggayPolygon = createBaranggayBoundary(currentBaranggayPolygon, [
      currentBaranggayCoords,
    ]);
  });
});

displayReports(
  "http://localhost/MapaAyos/api/reports?mode=getReports&status=active",
  infoContainer
);

if (selectFilterInput) {
  selectFilterInput.addEventListener("change", (e) => {
    const selectedFilter = e.target.value;
    removeAllPins();

    if (selectedFilter === "all-active") {
      displayReports(
        "http://localhost/MapaAyos/api/reports?mode=getReports&status=active",
        infoContainer
      );
    } else if (selectedFilter === "my-reports") {
      displayReports(
        `http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=${currentUser}&status=all`,
        infoContainer
      );
    } else if (selectedFilter === "my-pending") {
      displayReports(
        `http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=${currentUser}&status=pending`,
        infoContainer
      );
    } else if (selectedFilter === "my-active") {
      displayReports(
        `http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=${currentUser}&status=active`,
        infoContainer
      );
    }
  });
}
