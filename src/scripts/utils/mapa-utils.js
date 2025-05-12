import { getAddressFromCoords } from "./api-utils.js";
import { fetchAPI } from "./api-utils.js";

function isInBaranggay(point, polygon) {
  const [x, y] = point;
  let inside = false;

  for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
    const [xi, yi] = polygon[i];
    const [xj, yj] = polygon[j];

    const intersect =
      yi > y !== yj > y &&
      x < ((xj - xi) * (y - yi)) / (yj - yi + Number.EPSILON) + xi;

    if (intersect) inside = !inside;
  }

  return inside;
}

function createBaranggayBoundary(currentBaranggayPolygon, baranggayCoords) {
  if (currentBaranggayPolygon) {
    map.removeLayer(currentBaranggayPolygon);
  }

  // Create new polygon
  currentBaranggayPolygon = L.polygon(baranggayCoords, {
    color: "#9FE0B1",
    fillColor: "#BBD185",
    fillOpacity: 0.1,
    opacity: 0.5,
  }).addTo(map);

  // Fit and restrict map to new polygon
  map.fitBounds(currentBaranggayPolygon.getBounds());
  map.setMaxBounds(currentBaranggayPolygon.getBounds());

  return currentBaranggayPolygon;
}

function displayPopUp(lat, lng) {
  document.getElementById("latInput").value = lat;
  document.getElementById("lngInput").value = lng;

  // loading animation as address info is loading
  const loadingPopup = L.popup()
    .setLatLng([lat, lng])
    .setContent(
      `
      <div class="map-popup">
        <h4 class="popup-title">Fetching location...</h4>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle spin">
          <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
        </svg>
      </div>
    `
    )
    .openOn(map);

  // load info as soon as info is available
  getAddressFromCoords(lat, lng).then((coordsInfo) => {
    loadingPopup.setContent(`
      <div class="map-popup">
        <h4 class="popup-title">
          ${
            coordsInfo.road ||
            coordsInfo.city ||
            coordsInfo.suburb ||
            "Unknown location"
          }
        </h4>
        <p class="popup-subtitle">${coordsInfo.display_name || ""}</p>
        <button type="button" class="ma-btn popup-btn" data-bs-toggle="modal" data-bs-target="#reportModal">
          Report
        </button>
      </div>
    `);
  });
}

function displayReports(API_URL) {
  fetchAPI(API_URL).then((data) => {
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
}

function removeAllPins() {
  map.eachLayer((layer) => {
    if (layer instanceof L.Marker) {
      map.removeLayer(layer);
    }
  });
}

export {
  isInBaranggay,
  createBaranggayBoundary,
  displayPopUp,
  displayReports,
  removeAllPins,
};
