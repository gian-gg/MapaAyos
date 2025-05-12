import {
  isInBaranggay,
  displayReports,
  createBaranggayBoundary,
  displayPopUp,
  removeAllPins,
} from "./utils/mapa-utils.js";

import { fetchAPI } from "./utils/api-utils.js";
import { showToast } from "./utils/toast-utils.js";

let currentBaranggayCoords = null;
let currentBaranggayPolygon = null;

const infoContainer = document.getElementById("info-container");
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
    displayPopUp(lat, lng);
  } else {
    showToast("Error", "You clicked outside the baranggay boundary.");
  }
});

document
  .getElementById("selectBaranggayInput")
  .addEventListener("change", (e) => {
    const selectedBaranggay = e.target.value;

    infoContainer.classList.remove("hidden");

    fetchAPI(
      "http://localhost/MapaAyos/api/baranggay?baranggay=" + selectedBaranggay
    ).then((data) => {
      currentBaranggayCoords = JSON.parse(data.data[0].geojson)["coordinates"];

      infoContainer.innerHTML = `
        <img src="/MapaAyos/public/img/baranggays/${data.data[0].name}.jpg" alt="Report Image" />
        <h3>${data.data[0].name}</h3>
        <p>${data.data[0].city}, ${data.data[0].country}</p>
        <button id="infoContainerCloseButton">Close</button>

      `;

      const closeButton = document.getElementById("infoContainerCloseButton");
      if (closeButton) {
        closeButton.addEventListener("click", () => {
          infoContainer.classList.add("hidden");
        });
      }

      currentBaranggayPolygon = createBaranggayBoundary(
        currentBaranggayPolygon,
        [currentBaranggayCoords]
      );
    });
  });

displayReports(
  "http://localhost/MapaAyos/api/reports?mode=getReports&status=verified"
);
document.getElementById("selectFilterInput").addEventListener("change", (e) => {
  const selectedFilter = e.target.value;
  removeAllPins();

  if (selectedFilter === "all-verified") {
    displayReports(
      "http://localhost/MapaAyos/api/reports?mode=getReports&status=verified"
    );
  } else if (selectedFilter === "my-reports") {
    displayReports(
      `http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=${currentUser}&status=all`
    );
  } else if (selectedFilter === "my-pending") {
    displayReports(
      `http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=${currentUser}&status=pending`
    );
  } else if (selectedFilter === "my-verified") {
    displayReports(
      `http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=${currentUser}&status=verified`
    );
  }
});
