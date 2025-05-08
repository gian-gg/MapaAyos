import {
  isInBaranggay,
  displayReports,
  createBaranggayBoundary,
  displayPopUp,
} from "./utils/mapa-utils.js";

import { fetchAPI } from "./utils/api-utils.js";
import { showToast } from "./utils/toast-utils.js";

let currentBaranggayCoords = null;
let currentBaranggayPolygon = null;

// Event listener for map click
map.on("click", (e) => {
  const { lat, lng } = e.latlng;

  if (currentBaranggayCoords === null) {
    showToast("Error", "Please select a baranggay first before reporting.");
    return;
  }

  if (isInBaranggay([lat, lng], currentBaranggayCoords)) {
    console.log(`Clicked at Latitude: ${lat}, Longitude: ${lng}`);
    displayPopUp(lat, lng);
  } else {
    showToast("Error", "You clicked outside the baranggay boundary.");
  }
});

fetchAPI(
  "http://localhost/MapaAyos/api/reports?mode=getReportsByUserID&userID=" +
    currentUser
).then((data) => displayReports(data.reports));

document
  .getElementById("selectBaranggayInput")
  .addEventListener("change", (e) => {
    const selectedBaranggay = e.target.value;

    fetchAPI(
      "http://localhost/MapaAyos/api/baranggay?baranggay=" + selectedBaranggay
    ).then((data) => {
      currentBaranggayCoords = JSON.parse(data.data[0].geojson)["coordinates"];

      currentBaranggayPolygon = createBaranggayBoundary(
        currentBaranggayPolygon,
        [currentBaranggayCoords]
      );
    });
  });
