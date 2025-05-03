// // Function to get address from coordinates using Nominatim API, there might be a better approach than this pero this will do for now :3
function getAddressFromCoords(lat, lng) {
  const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

  return fetch(url, {
    headers: {
      "User-Agent": "MapaAyos/1.0", // Replace with your app name (required by Nominatim)
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data && data.address) {
        return {
          road: data.address.road || null,
          suburb: data.address.suburb || null,
          city:
            data.address.city ||
            data.address.town ||
            data.address.village ||
            null,
          display_name: data.display_name || null,
        };
      } else {
        return { error: "No address found" };
      }
    })
    .catch((error) => {
      return { error: error.message };
    });
}

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

// Initialize the map
const map = L.map("map").setView([10.3157, 123.8854], 6);
L.tileLayer(
  "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
  {
    attribution: "&copy; OpenStreetMap & CartoDB",
    subdomains: "abcd",
    maxZoom: 19,
  }
).addTo(map);

// Event listener for map click
map.on("click", (e) => {
  const { lat, lng } = e.latlng;
  console.log(`Clicked at Latitude: ${lat}, Longitude: ${lng}`);

  displayPopUp(lat, lng);
});

// Event listener for location found
map.on("locationfound", (e) => {
  const { lat, lng } = e.latlng;
  L.marker([lat, lng]).addTo(map).bindPopup("You are here!").openPopup();

  console.log(`Your location: Latitude: ${lat}, Longitude: ${lng}`);
});

map.on("locationerror", () => {
  alert("Location access denied or not available.");
});

// Add a button to locate the user's current location
document.getElementById("my-location-btn").addEventListener("click", () => {
  map.locate({
    setView: true,
    maxZoom: 16,
  });
});

// Display all reports on the map
reports.forEach((loc) => {
  L.marker([loc.lat, loc.lng]).addTo(map).bindPopup(`
      <div class="map-popup">
        <h4 class="popup-title">
          ${loc.title}
        </h4>
        <p class="popup-subtitle">${loc.description}</p>
      </div>
    `);
});
