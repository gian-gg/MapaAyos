// Initialize the map
const map = L.map("map", { zoomControl: false }).setView(
  [10.3157, 123.8854],
  9
);
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
  document.querySelector(".right-panel").style.display = "none";
  map.locate({
    setView: true,
    maxZoom: 16,
  });
});

// Event listener for location found
map.on("locationfound", (e) => {
  const { lat, lng } = e.latlng;
  const locationMarker = L.marker([lat, lng])
    .addTo(map)
    .bindPopup("You are here!")
    .openPopup();
  map.flyTo(locationMarker.getLatLng(), 11, { duration: 1 });

  console.log(`Your location: Latitude: ${lat}, Longitude: ${lng}`);
});

map.on("locationerror", () => {
  document.getElementById("ma-toast-title").innerText = "Error";
  document.getElementById("ma-toast-body").innerText =
    "Location access denied or not available.";
  new bootstrap.Toast(document.getElementById("ma-toast")).show();
});

// Custom zoom buttons event listeners
document.getElementById("zoom-in-btn").addEventListener("click", () => {
  map.zoomIn();
});

document.getElementById("zoom-out-btn").addEventListener("click", () => {
  map.zoomOut();
});
