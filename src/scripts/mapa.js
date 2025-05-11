import {
  isInBaranggay,
  createBaranggayBoundary,
  displayPopUp,
} from "./utils/mapa-utils.js";

import { fetchAPI } from "./utils/api-utils.js";
import { showToast } from "./utils/toast-utils.js";

let currentBaranggayCoords = null;
let currentBaranggayPolygon = null;

const infoContainer = document.getElementById("info-container");

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

fetchAPI(
  "http://localhost/MapaAyos/api/reports?mode=getReports&status=all"
).then((data) => {
  const reports = data.reports;

  if (!reports) return;

  reports.forEach((report) => {
    const marker = L.marker([report.lat, report.lng]).addTo(map);
    marker.setIcon(
      L.icon({
        iconUrl: "/MapaAyos/public/img/pins/default.png",
        iconSize: [24, 32],
        iconAnchor: [12, 12],
      })
    );

    marker.on("click", () => {
      infoContainer.classList.remove("hidden");
      infoContainer.innerHTML = `
        <h3>${report.title}</h3>
        <img src="/MapaAyos/public/uploads/reports/${report.imagePath}" alt="Report Image" />
        <p>${report.description}</p>
        <p>Status: ${report.status}</p>
        <p>Created At: ${report.createdAt}</p>
        <button id="infoContainerCloseButton">Close</button>
      `;

      // Reset all markers to default icon
      map.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
          layer.setIcon(
            L.icon({
              iconUrl: "/MapaAyos/public/img/pins/default.png",
              iconSize: [24, 32],
              iconAnchor: [12, 12],
            })
          );
        }
      });

      // Change the clicked marker icon to selected
      marker.setIcon(
        L.icon({
          iconUrl: "/MapaAyos/public/img/pins/selected.png",
          iconSize: [24, 32],
          iconAnchor: [12, 12],
        })
      );

      const closeButton = document.getElementById("infoContainerCloseButton");
      if (closeButton) {
        closeButton.addEventListener("click", () => {
          infoContainer.classList.add("hidden");
        });
      }
    });

    map.flyTo(marker.getLatLng(), 15, { duration: 1 });
  });
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
