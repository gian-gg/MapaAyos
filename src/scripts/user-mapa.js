// Function to display a popup with address information
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

// Event listener for map click
map.on("click", (e) => {
  const { lat, lng } = e.latlng;
  console.log(`Clicked at Latitude: ${lat}, Longitude: ${lng}`);

  displayPopUp(lat, lng);
});

const allReports = getAllReports(
  "http://localhost/MapaAyos/api/reports?mode=getByUserID&userID=" + currentUser
);

allReports.then((reports) => displayReports(reports, "all"));
