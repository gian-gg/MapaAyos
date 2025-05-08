import { getAddressFromCoords } from "./api-utils.js";

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

function displayReports(reports) {
  reports.forEach((loc) => {
    L.marker([loc.lat, loc.lng]).addTo(map).bindPopup(`
            <div class="map-popup">
              <h4 class="popup-title">
                ${loc.title}
              </h4>
              <p class="popup-subtitle">${loc.description}</p>
              <p class="popup-subtitle">Status: ${loc.status}</p>
            </div>
          `);
  });
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

export { isInBaranggay, createBaranggayBoundary, displayReports, displayPopUp };
