async function getAllReports(API_URL) {
  try {
    const response = await fetch(API_URL, {
      method: "GET",
      headers: {
        Authorization: "Bearer mapaayos123", // Replace with your actual token
      },
    });

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const data = await response.json();
    return data.reports;
  } catch (error) {
    console.error("Error fetching reports:", error);
    return [];
  }
}

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

// Add a button to locate the user's current location
document.getElementById("my-location-btn").addEventListener("click", () => {
  map.locate({
    setView: true,
    maxZoom: 16,
  });
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

function displayReports(reports) {
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
}
